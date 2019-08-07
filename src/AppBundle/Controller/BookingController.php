<?php


namespace AppBundle\Controller;


use AppBundle\Entity\Bookings\RentalBook;
use AppBundle\Entity\Bookings\AccommodationBook;
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

            $data = $form->getData();
            $noOfRooms = $data["no_of_rooms"];
            $noOfBeds = $data["no_of_beds"];
            $buildingId = $data["building_id"];
            $period = $data["start_date"];
            $days = $data["days"];
            /** @var User $user */
            $user = $this->getUser();

            $rooms = $em->getRepository(Room::class)->getData($noOfRooms, $noOfBeds, $buildingId);
            $dates = $this->getDates($period, $days);//make start/end date

            $roomBooked = new AccommodationBook();
            $roomBooked->setUser($user);
            $roomBooked->setPeriodStart($dates["start"]);
            $roomBooked->setPeriodEnd($dates["end"]);

            foreach ($rooms as $room) {
                /** @var Room $roomDB */
                $roomDB = $em->getRepository(Room::class)->find($room["id"]);
                $roomBooked->addRoom($roomDB);
            }
            $em->persist($roomBooked);
            $em->flush();

            $buildingName = $rooms[0]["building_name"];
            $cityName = $rooms[0]["city_name"];
            $price = $rooms[0]["price"];

            return $this->render("booking/accommodation.html.twig", [
                "user" => $user->getUsername(),
                "dateStart" => $dates["start"],
                "dateEnd" => $dates["end"],
                "building" => $buildingName,
                "city" => $cityName,
                "totalPrice" => $days * $price * $noOfRooms,
                "price" => $price,
                "beds" => $noOfBeds,
                "days" => $days,
                "rooms" => $noOfRooms
            ]);
        }
        return new Response("Please refresh page");
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

            $data = $form->getData();
            $carId = $data["car_id"];
            $period = $data["start_date"];
            $days = $data["days"];
            /** @var User $user */
            $user = $this->getUser();
            $dates = $this->getDates($period, $days);//make start/end date

            /** @var Car $car */
            $car = $em->getRepository(Car::class)->find($carId);
            $carBooked = new RentalBook();
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
        return new Response("Please refresh page");
    }


    private function getDates($period, $days)
    {
        $dateStart = DateTime::createFromFormat('d-m-Y', $period, new DateTimeZone("Europe/Kiev"));
        $dateEnd = DateTime::createFromFormat('d-m-Y', $period, new DateTimeZone("Europe/Kiev"));
        $dateEnd->modify('+' . $days . ' day');

        return ["start" => $dateStart, "end" => $dateEnd];
    }
}