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
     * @param $number integer
     * @return array
     */
    public function getAccommodationResult($city, $number)
    {
        return $this->createQueryBuilder('c')
            ->select(
                "b.name as building_name, r.number as room_name, r.beds, r.id as room_id, b.id as building_id")
            ->leftJoin('c.building', "b")
            ->leftJoin("b.room", "r")
            ->where("c.name = :city")
            ->andWhere("r.available = 1")
            ->andWhere("r.beds >= :number")
            ->setParameters(["city" => $city, "number" => $number])
            ->getQuery()
            ->execute();
    }
}