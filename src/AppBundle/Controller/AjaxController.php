<?php


namespace AppBundle\Controller;


use AppBundle\Entity\City;
use Doctrine\DBAL\DBALException;
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
        $data = $request->request->get("data");
        //one for GET other for POST
        if (is_null($this->em)) {
            $city = $this->getDoctrine()->getRepository(City::class)->getNamesLike($data);
        } else {
            $city = $this->em->getRepository(City::class)->getNamesLike($data);
        }
        if (empty($city)) {
            return new JsonResponse(json_encode(null));
        } else {
            $response = ["result" => $city, "position" => strlen($data)];
            return new JsonResponse(json_encode($response));
        }
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