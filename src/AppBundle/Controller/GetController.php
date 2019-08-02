<?php


namespace AppBundle\Controller;


use AppBundle\Entity\City;
use AppBundle\Form\AccommodationSearch;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetController extends Controller
{
    /**
     * @Route("/cazare", name="accommodation_get", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function accommodation(Request $request)
    {
//        $info = null;
//        if ($request->query->has("beds")) {
//            $info = $request->query->get("beds");
//            $info = ceil($info / 4);
//        }
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(AccommodationSearch::class);
        $cities = $em->getRepository(City::class)->getRandom();
        /** @var City $city */
        foreach ($cities as $city) {
            $average = $em->getRepository(City::class)->getAveragePriceForCity($city->getName())[0]["average"];
            $city->setAveragePrice(floor($average));
        }
        return $this->render("get/accommodation.html.twig", [
            "form" => $form->createView(),
            "cities" => $cities,
//            "info" => $info
        ]);
    }
}