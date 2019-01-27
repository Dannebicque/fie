<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Entity\PersonnelFormation;
use App\Events;
use App\Repository\EtudiantRepository;
use App\Repository\FormationRepository;
use App\Repository\PersonnelFormationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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
     * @Route("/connexion", name="security_login")
     * @param AuthenticationUtils $helper
     *
     * @return Response
     */
    public function login(AuthenticationUtils $helper): Response
    {
        return $this->render('security/login.html.twig', [
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

    /**
     * @Route("/premiere-connexion", name="security_first_connexion")
     */
    public function firstConnexion(FlashBagInterface $flashBag, Request $request, EtudiantRepository $etudiantRepository, EntityManagerInterface $entityManager, \Swift_Mailer $mailer) {

        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $numetudiant = $request->request->get('numetu');

            $etudiant = $etudiantRepository->findOneBy(['email' => $email, 'numetudiant' => $numetudiant]);
            if ($etudiant) {
                $random = $random = substr(md5(mt_rand()), 0, 7);;
                $pass = $this->encoder->encodePassword($etudiant, $random);
                $etudiant->setPassword($pass);
                $entityManager->persist($etudiant);
                $entityManager->flush();
                //envoi d'un mail de confirmation
                $mail = new \Swift_Message();

                $mail
                    ->setFrom([Events::MAIL_EXPEDITEUR => 'Forum IUT Entreprise, IUT de Troyes'])
                    ->setTo([$etudiant->getEmail()])
                    ->setSubject('Activation de votre compte pour le FORUM IUT Entreprise')
                    ->setBody($this->renderView('mails/first_connexion.html.twig', ['etudiant' => $etudiant, 'password' => $random]),
                        'text/plain')
                    ->setReplyTo(Events::MAIL_EXPEDITEUR);

                $mailer->send($mail);

                $flashBag->add('success', 'Activation réussie. Un email vous a été envoyé avec les informations de connexion.');
                return $this->redirectToRoute('security_login');

            } else {
                return $this->redirectToRoute('security_first_connexion', ['error' => 'Les informations saisies sont incorrectes']);
            }
        }

        return $this->render('security/firstConnexion.html.twig');
    }
}
