<?php


namespace AppBundle\Controller;


use AppBundle\Entity\User;
use AppBundle\Form\UserRegistrationForm;
use AppBundle\Security\LoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends Controller
{
    /**
     * @Route("/register", name="user_register")
     * @param Request $request
     * @return Response
     */
    public function registerAction(Request $request)
    {
        $form = $this->createForm(UserRegistrationForm::class);
        $form->add('submit', SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $form->getData();
            $user->setRoles(["ROLE_USER"]);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->get('security.authentication.guard_handler')
                ->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $this->get(LoginFormAuthenticator::class),
                    'main'
                );
        }

        return $this->render(
            'security/register.html.twig', [
                "form" => $form->createView()
            ]);
    }
}