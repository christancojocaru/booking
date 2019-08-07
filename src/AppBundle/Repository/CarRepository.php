<?php


namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class CarRepository extends EntityRepository
{
    public function getRentalResult($city, $seats, $fuel, $date)
    {
        $query = $this->createQueryBuilder("c")
            ->select("c.price, c.model, c.seats, c.fuel, c.gearType, c.id, c.image")
            ->leftJoin("c.city", "city")
            ->leftJoin("c.carBooked", "rent")
            ->where("city.name = :city")
            ->andWhere("c.seats >= :seats")
            ->andWhere(":date NOT BETWEEN rent.period_start AND rent.period_end")
            ->orderBy("c.price", "ASC");

        if ($fuel == "false") {
            return $query
                ->setParameters(["city" => $city, "seats" => $seats, "date" => $date])
                ->getQuery()
                ->execute();
        } else {
            return $query
                ->andWhere("c.fuel = :fuel")
                ->setParameters(["city" => $city, "seats" => $seats, "date" => $date, "fuel" => $fuel])
                ->getQuery()
                ->execute();
        }
    }

    public function getRentalRaw($city, $seats, $fuel, $date)
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
                         WHERE  city.name = :city
                AND car.fuel = :fuel
                AND car.seats >= :seats
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
                         WHERE  city.name = :city
                AND car.fuel = :fuel
                AND car.seats >= :seats
                AND :dateString NOT BETWEEN rental_book.period_start AND
                rental_book.period_end)) AS result
                ORDER  BY result.price
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(["city" => $city, "seats" => $seats, "dateString" => $date, "fuel" => $fuel]);

        return $stmt->fetchAll();
    }
}

//SELECT *
//FROM   ((SELECT car.model,
//                car.seats,
//                car.gear_type,
//                car.fuel,
//                car.id,
//                car.price,
//                car.image,
//                rental_book.period_start,
//                rental_book.period_end
//         FROM   car
//                LEFT JOIN city
//                       ON car.city_id = city.id
//                LEFT JOIN rental_book
//                       ON car.id = rental_book.car_id
//         WHERE  city.NAME = "bucuresti"
//AND car.fuel = "benzina"
//AND car.seats >= 5
//AND rental_book.id IS NULL)
//        UNION
//        (SELECT car.model,
//                car.seats,
//                car.gear_type,
//                car.fuel,
//                car.id,
//                car.price,
//                car.image,
//                rental_book.period_start,
//                rental_book.period_end
//         FROM   car
//                LEFT JOIN city
//                       ON car.city_id = city.id
//                LEFT JOIN rental_book
//                       ON rental_book.car_id = car.id
//         WHERE  city.NAME = "bucuresti"
//AND car.fuel = "benzina"
//AND car.seats >= 5
//AND "2019-08-10" NOT BETWEEN rental_book.period_start AND
//rental_book.period_end)) AS result
//ORDER  BY result.price