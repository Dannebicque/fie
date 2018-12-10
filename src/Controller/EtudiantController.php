<?php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Repository\CandidatureRepository;
use App\Repository\OffreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class EtudiantController extends AbstractController
{
    /**
     * @Route("/etudiant", name="etudiant_index")
     */
    public function index()
    {
        /** @var Etudiant $user */
        $user = $this->getUser();

        return $this->render('etudiant/index.html.twig', [
            'etudiant' => $user,
            'offres' => $user->getDiplome()->getOffres(),
            'candidatures' => $user->getCandidatures()
        ]);
    }
}
