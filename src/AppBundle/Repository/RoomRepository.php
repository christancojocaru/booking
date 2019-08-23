<?php


namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class RoomRepository extends EntityRepository
{
    public function getData($noOfRooms, $noOfBeds, $buildingId)
    {
        return $this->createQueryBuilder('r')
            ->select("r.id, r.price, b.name AS building_name, c.name AS city_name")
            ->leftJoin("r.building", "b")
            ->leftJoin("b.city", "c")
            ->where("b.id = :building_id")
            ->andWhere("r.beds = :noOfbeds")
            ->setMaxResults($noOfRooms)
            ->setParameters(["building_id" => $buildingId, "noOfbeds" => $noOfBeds])
            ->getQuery()
            ->execute();
    }
}