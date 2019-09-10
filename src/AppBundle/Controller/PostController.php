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
        /** @var DateTime $startDate */
        $startDate = $data["startDate"];
        /** @var DateTime $endDate */
        $endDate = $data["endDate"];
        $startDateString = $startDate->format("Y-m-d");
        $endDateString = $endDate->format("Y-m-d");
        $days = $startDate->diff($endDate)->days;

        try{
            $results = $em->getRepository(City::class)->getData($city, $beds, $startDateString, $endDateString, $days);
        }catch (DBALException $exception) {
            $results = 0;
        }

        foreach ($results as $key => $result) {
            $arrayBuildingName= explode(" ", $result["building_name"]);
            $arrayCityName = explode(" ", $city);
            $arrayBuildingName = $this->arrayToLower($arrayBuildingName);
            $arrayCityName = $this->arrayToLower($arrayCityName);
            $arrayBuildingImage = array_diff($arrayBuildingName, $arrayCityName);
            $buildingImage = implode(" ", $arrayBuildingImage);
            $buildingImage = ucwords($buildingImage, " ");
            $results[$key]["building_image"] = $buildingImage;
        }

        return $this->render("post/accommodation.html.twig", [
            "results" => $results,
            "beds" => $beds,
            "city" => $city,
            "startDate" => $startDate,
            "endDate" => $endDate,
            "count" => count($results),
            "days" => $days
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
        /** @var DateTime $startDate */
        $startDate = $data["startDate"];
        /** @var DateTime $endDate */
        $endDate = $data["endDate"];
        $startDateAsString = $startDate->format("Y-m-d");
        $endDateAsString = $endDate->format("Y-m-d");

        try{
            $results = $em->getRepository(Car::class)->getData($city, $seats, $fuel, $startDateAsString, $endDateAsString);
        }catch (DBALException $exception) {
            $results = 0;
        }

        return $this->render("post/rental.html.twig", [
            "results" => $results,
            "city" => $city,
            "startDate" => $startDate,
            "endDate" => $endDate,
            "count" => count($results),
        ]);
    }

    private function arrayToLower(array $data)
    {
        foreach ($data as $key => $word) {
            $word = mb_strtolower($word, "UTF-8");
            $data[$key] = $word;
        }
        return $data;
    }
}