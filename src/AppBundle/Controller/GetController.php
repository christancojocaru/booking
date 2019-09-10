<?php


namespace AppBundle\Controller;


use AppBundle\Entity\Bookings\AccommodationBook;
use AppBundle\Entity\Car;
use AppBundle\Entity\City;
use AppBundle\Entity\User;
use AppBundle\Form\AccommodationSearch;
use AppBundle\Form\RentalSearch;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetController extends Controller
{
    /**
     * @Route("/home", name="home_get", methods={"GET"})
     * @Route("/", methods={"GET"})
     */
    public function home()
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(AccommodationSearch::class);
        $cities = $em->getRepository(City::class)->getRandom(2);

        /** @var City $city */
        foreach ($cities as $city) {
            $average = $em->getRepository(City::class)->getLowestPrice($city->getName())[0]["lowest"];
            $city->setAveragePrice(floor($average));
        }

        return $this->render("get/home.html.twig", [
            "form" => $form->createView(),
            "cities" => $cities,
        ]);
    }

    /**
     * @Route("/cazare", name="accommodation_get", methods={"GET"})
     * @return Response
     */
    public function accommodation()
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(AccommodationSearch::class);
        $cities = $em->getRepository(City::class)->getRandom(6);

        /** @var City $city */
        foreach ($cities as $city) {
            $average = $em->getRepository(City::class)->getAveragePriceForCity($city->getName())[0]["average"];
            $city->setAveragePrice(floor($average));
        }

        return $this->render("get/accommodation.html.twig", [
            "form" => $form->createView(),
            "cities" => $cities,
        ]);
    }

    /**
     * @Route("/inchirieri", name="rentals_get", methods={"GET"})
     */
    public function rentals()
    {
        /** @var User $user */
        $user = $this->getUser();
        $startDate = date("Y-m-d", time());
        $endDate = date_create($startDate);
        date_add($endDate, date_interval_create_from_date_string("1 days"));
        $endDate = date("Y-m-d", $endDate->getTimestamp());

        $hasReservation = $user->hasReservation();
        $results = null;
        $city = null;
        if ($hasReservation) {
            /** @var AccommodationBook $reservation */
            $reservation = $user->getAccommodationBook()->first();
            $city = $reservation->getCity();
            $results = $this->getDoctrine()->getRepository(Car::class)->getData($city, 4, "both", $startDate, $endDate);
            $startDate = $reservation->getPeriodStart();
            $endDate = $reservation->getPeriodEnd();
        }

        $form = $this->createForm(RentalSearch::class);

        return $this->render("get/rental.html.twig", [
            "form" => $form->createView(),
            "promo" => $results,
            "startDate" => $startDate,
            "endDate" => $endDate,
            "city" => $city,
        ]);
    }

    /**
     * @Route("/zboruri", name="flights_get", methods={"GET"})
     */
    public function flights()
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->render("get/flights.html.twig", [
            "user" => $user->getUsername()
        ]);
    }
}