<?php


namespace AppBundle\Controller;


use AppBundle\Entity\Bookings\CarBooked;
use AppBundle\Entity\Bookings\RoomBooked;
use AppBundle\Entity\Building;
use AppBundle\Entity\Car;
use AppBundle\Entity\Room;
use AppBundle\Entity\User;
use AppBundle\Form\AccommodationBooking;
use AppBundle\Form\RentalBooking;
use DateInterval;
use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BookingController
 * @package AppBundle\Controller\Bookings
 * @Route("/booking")
 */
class BookingController extends Controller
{
    /**
     * @Route("/cazare", methods={"POST"}, name="booking_accommodation_action")
     * @param Request $request
     * @return Response
     */
    public function bookingAccommodation(Request $request)
    {
        $form = $this->createForm(AccommodationBooking::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $noOfRooms = $form->getData()["no_of_rooms"];
            $noOfBeds = $form->getData()["no_of_beds"];
            $buildingId = $form->getData()["building_id"];
            $period = $form->getData()["start_date"];
            $days = $form->getData()["days"];
            $rooms = $em->getRepository(Room::class)->getData($noOfRooms, $noOfBeds, $buildingId);
            /** @var User $user */
            $user = $this->getUser();

            $dates = $this->getDates($period, $days);

            foreach ($rooms as $room) {
                $roomDB = $em->getRepository(Room::class)->find($room["id"]);
                $roomDB->setAvailable(false);
                $roomBooked = new RoomBooked();
                $roomBooked->setUser($user);
                $roomBooked->setRoom($roomDB);
                $roomBooked->setPeriodStart($dates["start"]);
                $roomBooked->setPeriodEnd($dates["end"]);
                $em->persist($roomBooked);
                $em->flush();
            }

            return $this->render("booking/accommodation.html.twig", [
                "user" => $user->getUsername(),
                "dateStart" => $dates["start"],
                "dateEnd" => $dates["end"],
                "building" => $rooms[0]["building_name"],
                "city" => $rooms[0]["city_name"],
                "totalPrice" => $days * $rooms[0]["price"] * $noOfRooms,
                "price" => $rooms[0]["price"],
                "beds" => $noOfBeds,
                "days" => $days,
                "rooms" => $noOfRooms
            ]);
        }

        return new Response("Internal Error");
    }

    /**
     * @Route("/inchirieri", methods={"POST"}, name="booking_rental_action")
     * @param Request $request
     * @return Response
     */
    public function bookingRental(Request $request)
    {
        $form = $this->createForm(RentalBooking::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $carId = $form->getData()["car_id"];
            $period = $form->getData()["start_date"];
            $days = $form->getData()["days"];
            $dates = $this->getDates($period, $days);

            /** @var User $user */
            $user = $this->getUser();

            /** @var Car $car */
            $car = $em->getRepository(Car::class)->find($carId);

            $car->setAvailable(false);
            $carBooked = new CarBooked();
            $carBooked->setUser($user);
            $carBooked->setCar($car);
            $carBooked->setPeriodStart($dates["start"]);
            $carBooked->setPeriodEnd($dates["end"]);
            $em->persist($carBooked);
            $em->flush();


            $price = $car->getPrice();
            return $this->render("booking/rental.html.twig", [
                "user" => $user->getUsername(),
                "dateStart" => $dates["start"],
                "dateEnd" => $dates["end"],
                "model" => $car->getModel(),
                "city" => $car->getCity()->getName(),
                "totalPrice" => $days * $price,
                "price" => $price,
                "days" => $days
            ]);
        }

        return new Response("Internal Error");
    }


    private function getDates($period, $days)
    {
        $dateStart = DateTime::createFromFormat('d-m-Y', $period, new DateTimeZone("Europe/Kiev"));
        $dateEnd = DateTime::createFromFormat('d-m-Y', $period, new DateTimeZone("Europe/Kiev"));
        $dateEnd->modify('+' . $days . ' day');

        return ["start" => $dateStart, "end" => $dateEnd];
    }
}