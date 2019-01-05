<?php

namespace App\Controller;

use App\Entity\Creneaux;
use App\Entity\Entreprise;
use App\Events;
use App\Form\EntrepriseType;
use App\Repository\CreneauxRepository;
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
     * @param Request                  $request
     * @param EventDispatcherInterface $eventDispatcher
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
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
    public function confirmation(Entreprise $entreprise): \Symfony\Component\HttpFoundation\Response
    {
        return $this->render('professionnel/confirmation.html.twig', [
            'entreprise' => $entreprise
        ]);
    }

    /**
     * @param EventDispatcherInterface     $eventDispatcher
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param Request                      $request
     * @param RepresentantRepository       $representantRepository
     * @param Entreprise                   $entreprise
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/create-compte/{entreprise}", name="professionnel_creation_compte", methods={"POST"})
     *
     */
    public function createCompte(EventDispatcherInterface $eventDispatcher, UserPasswordEncoderInterface $passwordEncoder, Request $request, RepresentantRepository $representantRepository, Entreprise $entreprise): ?\Symfony\Component\HttpFoundation\Response
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
    public function gestion(): \Symfony\Component\HttpFoundation\Response
    {

        return $this->render('professionnel/gestion.html.twig', [
            'representant' => $this->getUser()
        ]);
    }

    /**
     * @Route("/planning", name="professionnel_planning", methods="GET")
     * @param CreneauxRepository $creneauxRepository
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function planning(CreneauxRepository $creneauxRepository): \Symfony\Component\HttpFoundation\Response
    {
        //todo: voir les créneaux de l'entreprise / offre ou un seul créneau par entreprise
        return $this->render('professionnel/planning.html.twig', [
            'entreprise' => $this->getUser()->getEntreprise(),
            'creneaux' => Creneaux::TAB_CRENEAUX,
            'occupation' =>$creneauxRepository->findByEntreprise($this->getUser()->getEntreprise())
        ]);
    }
}

