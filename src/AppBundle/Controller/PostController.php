<?php


namespace AppBundle\Controller;


use AppBundle\Entity\Car;
use AppBundle\Entity\City;
use AppBundle\Form\AccommodationSearch;
use AppBundle\Form\RentalSearch;
use DateTime;
use Doctrine\DBAL\DBALException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends Controller
{
    /**
     * @Route("/cazare", name="accommodation_post", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function accommodation(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(AccommodationSearch::class);
        $form->handleRequest($request);

        $data = $form->getData();
        $city = $data["location"];
        $beds = explode(" ", $data["number"])[0];//form.number is text LIKE "2 persoane"

        try{
            $results = $em->getRepository(City::class)->getAccommodationResult($city, $beds);
        }catch (DBALException $exception) {
            $results = 0;
        }

        return $this->render("post/accommodation.html.twig", [
            "results" => $results,
            "beds" => $beds,
            "city" => $city,
            "date" => $data["date"],
            "count" => count($results),
        ]);
    }

    /**
     * @Route("/inchirieri", name="rental_post", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function rental(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(RentalSearch::class);
        $form->handleRequest($request);

        $data = $form->getData();
        $city = $data["location"];
        $seats = $data["seats"];
        $fuel = $data["fuel"];
        /** @var DateTime $date */
        $date = $data["date"];
        $dateAsString = $date->format("Y-m-d");

        try{
            $results = $em->getRepository(Car::class)->getRentalRaw($city, $seats, $fuel, $date->format("Y-m-d"));
        }catch (DBALException $exception) {
            $results = 0;
        }

        return $this->render("post/rental.html.twig", [
            "results" => $results,
            "city" => $city,
            "date" => $date,
            "count" => count($results),
        ]);
    }
}