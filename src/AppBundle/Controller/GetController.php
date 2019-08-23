<?php


namespace AppBundle\Controller;


use AppBundle\Entity\Bookings\AccommodationBook;
use AppBundle\Entity\Bookings\RoomBooked;
use AppBundle\Entity\City;
use AppBundle\Entity\Room;
use AppBundle\Entity\User;
use AppBundle\Form\AccommodationSearch;
use AppBundle\Form\RentalSearch;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
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
        $form = $this->createForm(RentalSearch::class);

        return $this->render("get/rental.html.twig", [
            "form" => $form->createView()
        ]);
    }
}