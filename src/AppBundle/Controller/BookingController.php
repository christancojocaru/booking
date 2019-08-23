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
use function GuzzleHttp\Psr7\str;
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
            $startDate = $this->createDateFromString($data["start_date"]);
            $endDate = $this->createDateFromString($data["end_date"]);
            /** @var User $user */
            $user = $this->getUser();

            $rooms = $em->getRepository(Room::class)->getData($noOfRooms, $noOfBeds, $buildingId);

            $roomBooked = new AccommodationBook();
            $roomBooked->setUser($user);
            $roomBooked->setPeriodStart($startDate);
            $roomBooked->setPeriodEnd($endDate);

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
                "dateStart" => $startDate,
                "dateEnd" => $endDate,
                "building" => $buildingName,
                "city" => $cityName,
                "totalPrice" => $roomBooked->getDays() * $price * $noOfRooms,
                "price" => $price,
                "beds" => $noOfBeds,
                "days" => $roomBooked->getDays(),
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
            $startDate = $this->createDateFromString($data["start_date"]);
            $endDate = $this->createDateFromString($data["end_date"]);
            /** @var User $user */
            $user = $this->getUser();

            /** @var Car $car */
            $car = $em->getRepository(Car::class)->find($carId);
            $carBooked = new RentalBook();
            $carBooked->setUser($user);
            $carBooked->setCar($car);
            $carBooked->setPeriodStart($startDate);
            $carBooked->setPeriodEnd($endDate);
            $em->persist($carBooked);
            $em->flush();

            $price = $car->getPrice();
            $diff = $startDate->diff($endDate);
            $days = $diff->days;
            return $this->render("booking/rental.html.twig", [
                "user" => $user->getUsername(),
                "dateStart" => $startDate,
                "dateEnd" => $endDate,
                "model" => $car->getModel(),
                "city" => $car->getCity()->getName(),
                "totalPrice" => $days * $price,
                "price" => $price,
                "days" => $days
            ]);
        }
        return new Response("Please refresh page");
    }

    private function createDateFromString($date)
    {
        return DateTime::createFromFormat('d-m-Y', $date, new DateTimeZone("Europe/Kiev"));
    }
}