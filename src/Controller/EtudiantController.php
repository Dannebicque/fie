<?php

namespace App\Controller;

use App\Entity\Creneaux;
use App\Entity\Offre;
use App\Repository\CreneauxRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class EtudiantController
 * @package App\Controller
 * @Route("/etudiant")
 */
class EtudiantController extends AbstractController
{
    /**
     * @Route("/", name="etudiant_index")
     */
    public function index(CreneauxRepository $creneauxRepository)
    {
        $creneaux = ['14:00', '14:10', '14:20'];

        return $this->render('etudiant/index.html.twig', [
            'offres' => $this->getUser()->getDiplome()->getOffres(),
            'creneaux' => Creneaux::TAB_CRENEAUX,
            'occupation' => $creneauxRepository->findByEtudiant($this->getUser())
        ]);
    }

    /**
     * @Route("/offre/{offre}", name="etudiant_offre_show")
     */
    public function offre(Offre $offre)
    {
        return $this->render('etudiant/offre.html.twig', [
            'offre' => $offre
        ]);
    }
}
