<?php


namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/home", name="home_action")
     */
    public function home()
    {
        return $this->render(
            "get/home.html.twig"
        );
    }

    /**
     * @Route("/zboruri", name="flights_action")
     */
    public function flights()
    {
        return $this->render(
            "get/flights.html.twig"
        );
    }

    /**
     * @Route("/inchirieri", name="rentals_action")
     */
    public function rentals()
    {
        return $this->render(
            "get/rentals.html.twig"
        );
    }

    /**
     * @Route("/response", name="response_action")
     */
    public function response()
    {
        return $this->render(
            "get/response.html.twig"
        );
    }
}