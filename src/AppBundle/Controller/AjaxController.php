<?php


namespace AppBundle\Controller;


use AppBundle\Entity\City;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AjaxController
 * @package AppBundle\Ajax
 * @Route("/ajax")
 */
class AjaxController extends Controller
{
    /** @var EntityManagerInterface $em */
    private $em;

    /**
     * @Route("/cities", methods={"GET","POST"})
     * @param Request $request
     * @return Response|null
     */
    public function searchCities(Request $request)
    {
        $data = $request->request->get('data');

        if (is_null($this->em)) {
            $cities = $this->getDoctrine()->getRepository(City::class)->getAllNames();
        } else {
            $cities = $this->em->getRepository(City::class)->getAllNames();
        }

        foreach ($cities as $city) {
            $city = strtolower($city);
            $pos = strpos($city, $data);
            if ($pos !== false) {
                $position = $pos + strlen($data) - 1;
                $response = ["result" => $city, "position" => $position];
                return new JsonResponse(json_encode($response));
            }
        }
        return null;
    }

    /**
     * @Route("/accommodation", methods={"GET","POST"})
     * @param Request $request
     * @return Response|null
     */
    public function searchAccommodation(Request $request)
    {
        $data = $request->request;



    }

    /**
     * @param EntityManagerInterface $entityManager
     * @required
     */
    public function setEntityManager(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }
}