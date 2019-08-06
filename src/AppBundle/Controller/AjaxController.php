<?php


namespace AppBundle\Controller;


use AppBundle\Entity\City;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
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
     * @return Response
     * @throws Exception
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
        $dataLen = strlen($data);
        $cityLen = strlen(strtolower($city));
        if ( ($dataLen == $cityLen) || empty($city)) {
            throw new Exception("Error");
        } else {
            $response = ["result" => $city, "position" => $dataLen - 1];
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