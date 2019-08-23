<?php


namespace AppBundle\Repository;


use AppBundle\Entity\Building;
use AppBundle\Entity\City;
use Doctrine\ORM\EntityRepository;

class BuildingRepository extends EntityRepository
{
    /**
     * @param $city string
     * @param $date object
     * @param $number integer
     */
    public function getAccommodationResult($city, $date, $number)
    {
        /** @var City $cityDB */
        $cityDB = $this->findOneBy(["city" => $city]);
        /** @var Building $building */
        $building = $cityDB->getBuildings();
        $building->getName();
        var_dump($cityDB);
    }
}