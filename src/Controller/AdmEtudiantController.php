<?php

namespace App\Controller;

use App\Entity\Creneaux;
use App\Entity\Diplome;
use App\Entity\Etudiant;
use App\Form\DiplomeType;
use App\Repository\CreneauxRepository;
use App\Repository\DiplomeRepository;
use App\Repository\EtudiantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/administration/etudiant")
 */
class AdmEtudiantController extends AbstractController
{
    /**
     * @Route("/", name="admEtudiant_index", methods="GET")
     */
    public function index(EtudiantRepository $etudiantRepository): Response
    {
        return $this->render('admEtudiant/index.html.twig', ['etudiants' => $etudiantRepository->findAll()]);
    }

    /**
     * @Route("/{id}", name="admEtudiant_show", methods="GET")
     */
    public function show(CreneauxRepository $creneauxRepository, Etudiant $etudiant): Response
    {
        return $this->render('admEtudiant/show.html.twig', [
            'etudiant' => $etudiant,
            'creneaux'   => Creneaux::TAB_CRENEAUX,
            'occupation' => $creneauxRepository->findByEtudiant($etudiant)]);
    }

    /**
     * @Route("/delete/{etudiant}/{creneau}", name="admEtudiant_delete_creneau", methods="DELETE")
     */
    public function deleteCreneaux(FlashBagInterface $flashBag, EntityManagerInterface $entityManager, Etudiant $etudiant, Creneaux $creneaux): Response
    {
        $entityManager->remove($creneaux);
        $entityManager->flush();
        $flashBag->add('success', 'Créneau supprimé');

        return $this->redirectToRoute('admEtudiant_show', ['id' => $etudiant->getId()]);

    }
}
