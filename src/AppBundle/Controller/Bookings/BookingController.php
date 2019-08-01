<?php


namespace AppBundle\Controller\Bookings;


use AppBundle\Entity\Bookings\RoomBooked;
use AppBundle\Entity\Room;
use AppBundle\Entity\User;
use AppBundle\Form\AccommodationBooking;
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

            $roomId = $form->getData()["room_id"];
            $period = $form->getData()["start_date"];
            $days = $form->getData()["days"];
            /** @var Room $room */
            $room = $em->getRepository(Room::class)->find($roomId);
            $building = $room->getBuilding()->getName();
            $city = $room->getBuilding()->getCity()->getName();
            /** @var User $user */
            $user = $this->getUser();

            $dates = $this->getDates($period, $days);

            $roomBooked = new RoomBooked();
            $roomBooked->setUser($user);
            $roomBooked->setRoom($room);
            $roomBooked->setPeriodStart($dates["start"]);
            $roomBooked->setPeriodEnd($dates["end"]);
            $em->persist($roomBooked);

            $room->setAvailable(false);
            $em->flush();

            return $this->render("booking/accommodation.html.twig", [
                "user" => $user->getUsername(),
                "dateStart" => $dates["start"],
                "dateEnd" => $dates["end"],
                "building" => $building,
                "city" => $city,
                "totalPrice" => $days * $room->getPrice(),
                "price" => $room->getPrice(),
                "room" => $room->getNumber(),
                "beds" => $room->getBeds()
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