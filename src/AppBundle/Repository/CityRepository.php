<?php


namespace AppBundle\Repository;


use AppBundle\Entity\City;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityRepository;

class CityRepository extends EntityRepository
{
    /**
     * @param $data
     * @return array
     */
    public function getNamesLike($data)
    {
        $cities = $this->createQueryBuilder('c')
            ->where('c.name LIKE :data')
            ->setParameters(["data" => $data."%"])
            ->getQuery()
            ->execute();
        $result = [];
        /** @var City $city */
        foreach ($cities as $city) {
            $result[] = $city->getName();
        }
        return $result;
    }

    /**
     * @param $city string
     * @param $beds integer
     * @param $startDate
     * @param $endDate
     * @return array
     * @throws DBALException
     */
    public function getData($city, $beds, $startDate, $endDate)
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        $sql = '
                SELECT result.*,
                       Sum(result.beds) AS bedsum
                FROM   ((SELECT b.name  AS building_name,
                                r.beds,
                                r.price,
                                r.id    AS room_id,
                                b.id    AS building_id,
                                c.image AS image
                         FROM   city AS c
                                LEFT JOIN building AS b
                                       ON b.city_id = c.id
                                LEFT JOIN room AS r
                                       ON r.building_id = b.id
                                LEFT JOIN accommodation_book_room AS abr
                                       ON r.id = abr.room_id
                                LEFT JOIN accommodation_book AS ab
                                       ON abr.accommodation_book_id = ab.id
                         WHERE  c.name = :city
                                AND IF(:beds >= 4, r.beds <= :beds, r.beds = :beds)
                                AND ab.id IS NULL)
                        UNION
                        (SELECT b.name  AS building_name,
                                r.beds,
                                r.price,
                                r.id    AS room_id,
                                b.id    AS building_id,
                                c.image AS image
                         FROM   city AS c
                                LEFT JOIN building AS b
                                       ON b.city_id = c.id
                                LEFT JOIN room AS r
                                       ON r.building_id = b.id
                                LEFT JOIN accommodation_book_room AS abr
                                       ON r.id = abr.room_id
                                LEFT JOIN accommodation_book AS ab
                                       ON abr.accommodation_book_id = ab.id
                         WHERE  c.name = :city
                                AND IF(:beds >= 4, r.beds <= :beds, r.beds = :beds)
                                AND :startDate NOT BETWEEN ab.period_start AND ab.period_end
                                AND :endDate NOT BETWEEN ab.period_start AND ab.period_end))
                       AS
                       result
                GROUP  BY result.building_name,
                          CASE
                            WHEN :beds > 4 THEN result.beds
                            ELSE 0
                          end
                HAVING bedsum >= :beds
                ORDER  BY result.price ASC,
                          result.building_name ASC,
                          result.beds ASC
                LIMIT 10
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['city' => $city, "beds" => $beds, "startDate" => $startDate, "endDate" => $endDate]);

        return $stmt->fetchAll();
    }

    public function getAveragePriceForCity($city)
    {
        return $this->createQueryBuilder("c")
            ->select("avg(r.price) as average")
            ->leftJoin('c.buildings', "b")
            ->leftJoin("b.rooms", "r")
            ->where("c.name = :city")
            ->setParameters(["city" => $city])
            ->getQuery()
            ->execute();
    }

    public function getLowestPrice($city)
    {
        return $this->createQueryBuilder("c")
            ->select("min(r.price) as lowest")
            ->leftJoin('c.buildings', "b")
            ->leftJoin("b.rooms", "r")
            ->where("c.name = :city")
            ->setParameters(["city" => $city])
            ->getQuery()
            ->execute();
    }

    /**
     * @param $results
     * @return mixed
     */
    public function getRandom($results)
    {
        return $this->createQueryBuilder("c")
            ->setFirstResult(rand(0, 40))
            ->setMaxResults($results)
            ->getQuery()
            ->execute();
    }
}