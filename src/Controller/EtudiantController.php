<?php

namespace App\Controller;

use App\Entity\Creneaux;
use App\Entity\Offre;
use App\Repository\CreneauxRepository;
use App\Repository\OffreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
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
        return $this->render('etudiant/index.html.twig', [
            'offres'     => $this->getUser()->getDiplome()->getOffres(),
            'creneaux'   => Creneaux::TAB_CRENEAUX,
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

    /**
     * @param Offre $offre
     * @Route("/ajax/rdv", name="etudiant_ajax_rdv")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function ajaxRdv(Request $request, OffreRepository $offreRepository, CreneauxRepository $creneauxRepository)
    {
        $offre = $request->request->get('offre');
        $offre = $offreRepository->find($offre);
        $creneauxOccupes = $creneauxRepository->findBy(['entreprise' => $offre->getEntreprise()->getId()]);

        $creneaux = [];

        foreach (Creneaux::TAB_CRENEAUX as $cr) {
            $creneaux[] = $cr;
        }

        //calcul des creneaux dispo
        foreach ($creneauxOccupes as $cr) {
            $id = array_search($cr->getHeure(), $creneaux);
            unset($creneaux[$id]); //on supprimer le créneau occupé
        }

        return $this->render('etudiant/ajaxRdv.html.twig', [
            'offre'    => $offre,
            'creneaux' => $creneaux
        ]);
    }

    /**
     * @param Offre $offre
     * @Route("/ajax/sauverdv/{offre}", name="etudiant_ajax_sauvegarde_rdv")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function ajaxPostRdv(FlashBagInterface $flashBag, EntityManagerInterface $entityManager, CreneauxRepository $creneauxRepository, Request $request, Offre $offre)
    {
        $cr = $request->request->get('creneau');

            //vérifier si le creneaux n'a pas été pris entre temps ?
        $creneaux = $creneauxRepository->findBy([
            'entreprise' => $offre->getEntreprise()->getId(),
            'heure'      => $cr
        ]);

        if (count($creneaux) === 0) {
            //pas réservé, on peut créer.
            $creneau = new Creneaux();
            $creneau->setEntreprise($offre->getEntreprise());
            $creneau->setHeure($cr);
            $creneau->setEtudiant($this->getUser());
            $entityManager->persist($creneau);
            $entityManager->flush();
            $flashBag->add('success', 'Créneau réservé.');
            return new JsonResponse('ok', 200);
            //message OK
        } else {
            $flashBag->add('error', 'Le créneau a été déjà été réservé..');
            return new JsonResponse('nok', 200);
        }
    }
}
