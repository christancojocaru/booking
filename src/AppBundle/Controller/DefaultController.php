<?php


namespace AppBundle\Controller;


use AppBundle\Entity\Building;
use AppBundle\Entity\City;
use AppBundle\Form\Accommodation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/home", name="home_action")
     */
    public function home()
    {
        return $this->render(
            "pages/home.html.twig"
        );
    }

    /**
     * @Route("/zboruri", name="flights_action")
     */
    public function flights()
    {
        return $this->render(
            "pages/flights.html.twig"
        );
    }

    /**
     * @Route("/cazare", name="accommodation_action")
     * @param Request $request
     * @return Response
     */
    public function accommodation(Request $request)
    {
        $form = $this->createForm(Accommodation::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $data = $form->getData();

            $city = $data["location"];
            /** @var \DateTime $date */
            $date = $data["date"];
            $number = $data["number"];

            $em = $this->getDoctrine()->getManager();
            $results = $em->getRepository(City::class)->getAccommodationResult($city, $number);

            return $this->render("result/accommodation.html.twig", [
                "results" => $results,
                "city" => $city,
                "date" => $date,
                "count" => count($results)
            ]);
        }

        return $this->render("pages/accommodation.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/inchirieri", name="rentals_action")
     */
    public function rentals()
    {
        return $this->render(
            "pages/rentals.html.twig"
        );
    }

    /**
     * @Route("/response", name="response_action")
     */
    public function response()
    {
        return $this->render(
            "pages/response.html.twig"
        );
    }
}