<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class EtudiantController extends AbstractController
{
    /**
     * @Route("/etudiant", name="etudiant_index")
     */
    public function index()
    {
        return $this->render('etudiant/index.html.twig', [
        ]);
    }
}
