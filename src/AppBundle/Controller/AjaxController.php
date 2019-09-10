<?php


namespace AppBundle\Controller;


use AppBundle\Entity\Bookings\AccommodationBook;
use AppBundle\Entity\Bookings\RentalBook;
use AppBundle\Entity\City;
use AppBundle\Entity\User;
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
     * @Route("/cities", methods={"POST"}, name="ajax_search_cities")
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function searchCities(Request $request)
    {
        $data = $request->request->get("data");
        $cities = $this->em->getRepository(City::class)->getNamesLike($data);
        $dataLen = strlen($data);
//        $cityLen = strlen(strtolower($city));
        if (empty($cities)) {
//        if ( ($dataLen == $cityLen) || empty($city)) {
            throw new Exception("Error");
        } else {
            foreach ($cities as $city) {
                $response[] = ["result" => $city, "position" => $dataLen - 1];
            }
//            $response = ["result" => $city, "position" => $dataLen - 1];
            return new JsonResponse(json_encode($response));
        }

    }

    /**
     * @Route("/cart", methods={"POST"}, name="ajax_delete_cart")
     * @param Request $request
     * @return Response
     */
    public function deleteCart(Request $request)
    {
        $data = $request->request->all();
        $userId = $data["user"];
        $bookType = $data["type"];
        $bookId = $data["id"];
        $user = $this->em->getRepository(User::class)->find($userId);

        $accommodationBooks = null;
        $rentalBooks = null;
        if (empty($bookId) && empty($bookType)) {
            $accommodationBooks = $this->em->getRepository(AccommodationBook::class)->findBy(["user" => $user]);
            $rentalBooks = $this->em->getRepository(RentalBook::class)->findBy(["user" => $user]);
        } else {
            switch ($bookType) {
                case "accommodation":
                    $accommodationBooks[] = $this->em->getRepository(AccommodationBook::class)->find($bookId);
                    break;
                case "rental":
                    $rentalBooks[] = $this->em->getRepository(RentalBook::class)->find($bookId);
                    break;
            }

        }
        if ($accommodationBooks) {
            foreach ($accommodationBooks as $accommodationBook) {
                $this->em->remove($accommodationBook);
            }
        }
        if ($rentalBooks) {
            foreach ($rentalBooks as $rentalBook) {
                $this->em->remove($rentalBook);
            }
        }
        $this->em->flush();

        return new Response("Done");
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