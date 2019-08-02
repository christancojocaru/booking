<?php


namespace AppBundle\Repository;


use AppBundle\Entity\City;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

class CityRepository extends EntityRepository
{
    /**
     * @return array
     */
    public function getAllNames()
    {
        $names = [];
        /** @var City $city */
        foreach ($this->findAll() as $city) {
            $names[] = $city->getName();
        }
        return $names;
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
                       Min(price) AS minPrice,
                       SUM(result.beds) AS bedsum
                FROM   (SELECT b.name AS building_name,
                               r.beds,
                               r.price,
                               r.id   AS room_id,
                               b.id   AS building_id
                        FROM   city AS c
                               left join building AS b
                                      ON b.city_id = c.id
                               left join room AS r
                                      ON r.building_id = b.id
                        WHERE  c.name = :city
                               AND r.beds >= IF(:beds > 4, "1", :beds)
                               AND r.available = 1) AS result
                WHERE  result.price < 99
                GROUP  BY result.building_name
                HAVING bedsum >= :beds
                ORDER  BY result.beds ASC,
                          result.price ASC 
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

    /**
     * @return mixed
     */
    public function getRandom()
    {
        return $this->createQueryBuilder("c")
            ->setFirstResult(rand(0, 40))
            ->setMaxResults(6)
            ->getQuery()
            ->execute();
    }
}
//
//SELECT b.name   AS building_name,
//               r.beds,
//               r.price,
//               r.id     AS room_id,
//               b.id     AS building_id,
//        	   IF(beds > 3, "MORE", "LESS") as 'M//L'
//        FROM   room AS r
//               LEFT JOIN building AS b
//                      ON r.building_id = b.id AND r.price < 99
//               LEFT JOIN city AS c
//                      ON b.city_id = c.id
//        WHERE  c.name = "Ploiesti"
//AND r.beds >= 4
//AND r.available = 1