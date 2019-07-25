<?php


namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class Actions extends Controller
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
     */
    public function accommodation()
    {
        return $this->render(
            "pages/accommodation.html.twig"
        );
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