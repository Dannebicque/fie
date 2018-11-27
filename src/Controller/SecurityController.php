<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Entity\PersonnelFormation;
use App\Repository\FormationRepository;
use App\Repository\PersonnelFormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class SecurityController
 * @package App\Controller
 */
class SecurityController extends AbstractController
{
    private $encoder;

    /**
     * SecurityController constructor.
     *
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }


    /**
     * @Route("/connexion/{message}", name="security_login")
     * @param AuthenticationUtils $helper
     *
     * @return Response
     */
    public function login(AuthenticationUtils $helper, $message = ''): Response
    {
        return $this->render('security/login.html.twig', [
            'message' => $message,
            // dernier username saisi (si il y en a un)
            'last_username' => $helper->getLastUsername(),
            // La derniere erreur de connexion (si il y en a une)
            'error' => $helper->getLastAuthenticationError(),
        ]);
    }

    /**
     * @Route("/deconnexion", name="security_logout")
     */
    public function logout(): void
    {
    }

    /**
     * @Route("/mot-de-passe-perdu", name="security_password_lost")
     * @param Request $request
     *
     * @return Response
     */
    public function passwordLost(Request $request): Response
    {
        $submittedToken = $request->request->get('token');

        if ($request->isMethod('POST') && $this->isCsrfTokenValid('password-lost', $submittedToken)) {

            //todo: password-lost : token + mail
            return $this->render('security/passwordLostConfirm.html.twig');
        }

        return $this->render('security/passwordLost.html.twig');
    }
}
