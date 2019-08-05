<?php


namespace AppBundle\Controller;


use AppBundle\Entity\City;
use AppBundle\Form\AccommodationSearch;
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
        /** @var DateTime $date */
        $date = $data["date"];
        $beds = explode(" ", $data["number"])[0];

        try{
            $results = $em->getRepository(City::class)->getAccommodationResult($city, $beds);
        }catch (DBALException $exception) {
            $results = 0;
        }

        return $this->render("post/accommodation.html.twig", [
            "results" => $results,
            "beds" => $beds,
            "city" => $city,
            "date" => $date,
            "count" => count($results),
        ]);

    }
}