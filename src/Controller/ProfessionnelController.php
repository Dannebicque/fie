<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Form\EntrepriseType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProfessionnelController extends AbstractController
{
    /**
     * @Route("/professionnel", name="professionnel_index")
     */
    public function index()
    {
        $entreprise = new Entreprise();
        $form = $this->createForm(EntrepriseType::class, $entreprise);


        return $this->render('professionnel/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
