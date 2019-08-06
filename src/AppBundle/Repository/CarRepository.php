<?php


namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class CarRepository extends EntityRepository
{
    public function getRentalResult($city)
    {
        return $this->createQueryBuilder("c")
            ->select("c.price, c.model, c.seats, c.fuel, c.gearType, c.id, c.image")
            ->leftJoin("c.city", "city")
            ->where("city.name = :city")
            ->andWhere("c.available = true")
            ->setParameters(["city" => $city])
            ->orderBy("c.price", "ASC")
            ->getQuery()
            ->execute();
    }
}