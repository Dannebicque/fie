<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Form\EntrepriseType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProfessionnelController extends AbstractController
{
    /**
     * @Route("/professionnel", name="professionnel_index")
     */
    public function index(Request $request)
    {
        $entreprise = new Entreprise();
        $form = $this->createForm(EntrepriseType::class, $entreprise);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entreprise);
            $em->flush();

            return $this->redirectToRoute('professionnel_confirmation', ['entreprise' => $entreprise]);
        }

        return $this->render('professionnel/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Entreprise $entreprise
     * @Route("/confirmation/{entreprise}", name="professionnel_confirmation")
     */
    public function confirmation(Entreprise $entreprise) {
        return $this->render('professionnel/confirmation.html.twig', [
            'entreprise' => $entreprise
        ]);
    }

    /**
     * @param Entreprise $entreprise
     * @Route("/create-compte", name="entreprise_creation_compte")
     */
    public function createCompte(Entreprise $entreprise) {

    }
}

