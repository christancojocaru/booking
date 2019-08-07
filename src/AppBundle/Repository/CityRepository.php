<?php


namespace AppBundle\Repository;


use AppBundle\Entity\City;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityRepository;

class CityRepository extends EntityRepository
{
    /**
     * @param $data
     * @return string
     */
    public function getNamesLike($data)
    {
        $city = $this->createQueryBuilder('c')
            ->where('c.name LIKE :data')
            ->setParameters(["data" => $data."%"])
            ->getQuery()
            ->execute();
        $city = $city[0];
        /** @var City $city */
        return $city->getName();
    }

    /**
     * @param $city string
     * @param $beds integer
     * @return array
     * @throws DBALException
     */
    public function getAccommodationResult($city, $beds)
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        $sql = '
                SELECT result.*,
                       SUM(result.beds) AS bedsum
                FROM   (SELECT b.name AS building_name,
                               r.beds,
                               r.price,
                               r.id   AS room_id,
                               b.id   AS building_id,
                               c.image AS image
                        FROM   city AS c
                               left join building AS b
                                      ON b.city_id = c.id
                               left join room AS r
                                      ON r.building_id = b.id
                        WHERE  c.name = :city
                               AND IF(:beds >= 4, r.beds <= :beds, r.beds = :beds)) AS result
               
                GROUP  BY result.building_name, CASE WHEN :beds >= 4 THEN result.beds ELSE 0 END
                HAVING bedsum >= :beds
                ORDER  BY result.price ASC,
                          result.building_name ASC,
                          result.beds ASC
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['city' => $city, "beds" => $beds]);

        return $stmt->fetchAll();
    }

    public function getAveragePriceForCity($city)
    {
        return $this->createQueryBuilder("c")
            ->select("avg(r.price) as average")
            ->leftJoin('c.building', "b")
            ->leftJoin("b.room", "r")
            ->where("c.name = :city")
            ->setParameters(["city" => $city])
            ->getQuery()
            ->execute();
    }

    public function getLowestPrice($city)
    {
        return $this->createQueryBuilder("c")
            ->select("min(r.price) as lowest")
            ->leftJoin('c.building', "b")
            ->leftJoin("b.room", "r")
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