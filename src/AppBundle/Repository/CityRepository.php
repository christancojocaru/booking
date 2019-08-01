<?php


namespace AppBundle\Repository;


use AppBundle\Entity\City;
use Doctrine\ORM\EntityRepository;

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
     */
    public function getAccommodationResult($city, $beds)
    {
        return $this->createQueryBuilder('c')
            ->select(
                "b.name as building_name, r.number as room_name, r.beds, r.price, r.id as room_id, b.id as building_id")
            ->leftJoin('c.building', "b")
            ->leftJoin("b.room", "r")
            ->where("c.name = :city")
            ->andWhere("r.available = true")
            ->andWhere("r.beds >= :number")
            ->setParameters(["city" => $city, "number" => $beds])
            ->groupBy("b")
            ->orderBy("r.price", "ASC")
            ->getQuery()
            ->execute();
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