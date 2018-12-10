<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Events;
use App\Form\EntrepriseType;
use App\Repository\RepresentantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class ProfessionnelController
 * @package App\Controller
 * @Route("/professionnel")
 */
class ProfessionnelController extends AbstractController
{
    /**
     * @Route("/", name="professionnel_index")
     */
    public function index(Request $request, EventDispatcherInterface $eventDispatcher)
    {
        $entreprise = new Entreprise();
        $form = $this->createForm(EntrepriseType::class, $entreprise);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entreprise);
            $em->flush();

            //envoi d'un mail de confirmation
            $event = new GenericEvent($entreprise);
            $eventDispatcher->dispatch(Events::CONFIRMATION_INSCRIPTION, $event);

            return $this->redirectToRoute('professionnel_confirmation', ['entreprise' => $entreprise->getId()]);
        }

        return $this->render('professionnel/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Entreprise $entreprise
     * @Route("/confirmation/{entreprise}", name="professionnel_confirmation")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function confirmation(Entreprise $entreprise)
    {
        return $this->render('professionnel/confirmation.html.twig', [
            'entreprise' => $entreprise
        ]);
    }

    /**
     * @param Entreprise $entreprise
     * @Route("/create-compte/{entreprise}", name="professionnel_creation_compte", methods={"POST"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createCompte(EventDispatcherInterface $eventDispatcher, UserPasswordEncoderInterface $passwordEncoder, Request $request, RepresentantRepository $representantRepository, Entreprise $entreprise)
    {
        $email = $request->request->get('email');
        $responsable = $representantRepository->findOneBy(['email' => $email, 'entreprise' => $entreprise->getId()]);
        if ($responsable !== null) {
            //création du compte
            $pass = $request->request->get('pass1');
            $password = $passwordEncoder->encodePassword($responsable, $pass);
            $responsable->setPassword($password);
            $responsable->setRoles(['ROLE_ENTREPRISE']);
            $em = $this->getDoctrine()->getManager();
            $em->persist($responsable);
            $em->flush();

            //envoi d'un mail de confirmation
            $event = new GenericEvent($responsable);
            $eventDispatcher->dispatch(Events::CONFIRMATION_CREATION_COMPTE, $event);

            return $this->render('professionnel/create_compte.html.twig', [
                'entreprise' => $entreprise
            ]);
        }
    }

    /**
     * @Route("/gestion", name="professionnel_gestion")
     */
    public function gestion() {

        return $this->render('professionnel/gestion.html.twig', [
            'representant' => $this->getUser()
        ]);
    }
}

