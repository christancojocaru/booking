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
}