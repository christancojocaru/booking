<?php


namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class CarRepository extends EntityRepository
{
    public function getData($city, $seats, $fuel, $startDate, $endDate)
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        $sql = '
                SELECT *
                FROM   ((SELECT car.model,
                                car.seats,
                                car.gear_type,
                                car.fuel,
                                car.id,
                                car.price,
                                car.image
                         FROM   car
                                LEFT JOIN city
                                       ON car.city_id = city.id
                                LEFT JOIN rental_book
                                       ON car.id = rental_book.car_id
                         WHERE  city.NAME = :city
                                AND IF(:fuel = "both", car.fuel IN ("motorina", "benzina"), car.fuel = :fuel)
                                AND car.seats BETWEEN :seats AND :seats + 1
                                AND rental_book.id IS NULL)
                        UNION
                        (SELECT car.model,
                                car.seats,
                                car.gear_type,
                                car.fuel,
                                car.id,
                                car.price,
                                car.image
                         FROM   car
                                LEFT JOIN city
                                       ON car.city_id = city.id
                                LEFT JOIN rental_book
                                       ON rental_book.car_id = car.id
                         WHERE  city.NAME = :city
                                AND IF(:fuel = "both", car.fuel IN ("motorina", "benzina"), car.fuel = :fuel)
                                AND car.seats BETWEEN :seats AND :seats + 1
                                AND :startDate NOT BETWEEN rental_book.period_start AND
                                                                 rental_book.period_end
                                AND :endDate NOT BETWEEN rental_book.period_start AND
                                                               rental_book.period_end)) AS
                       result
                ORDER  BY result.seats ASC,
                          result.price ASC  
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(["city" => $city, "seats" => $seats, "startDate" => $startDate, "endDate" => $endDate, "fuel" => $fuel]);

        return $stmt->fetchAll();
    }
}