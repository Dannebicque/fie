<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Entity\Offre;
use App\Form\OffreType;
use App\Repository\OffreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

// Include PhpSpreadsheet required namespaces
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * @Route("/professionnel/gestion/offre")
 */
class RepresentantOffreController extends AbstractController
{
    /**
     * @Route("/new/{entreprise}", name="professionnel_offre_new", methods="GET|POST")
     */
    public function new(Request $request, Entreprise $entreprise): Response
    {
        $offre = new Offre();
        $offre->setEntreprise($entreprise);
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($offre);
            $em->flush();

            return $this->redirectToRoute('professionnel_gestion');
        }

        return $this->render('professionneloffre/new.html.twig', [
            'offre' => $offre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="professionnel_offre_show", methods="GET")
     */
    public function show(Offre $offre): Response
    {
        return $this->render('professionneloffre/show.html.twig', ['offre' => $offre]);
    }

    /**
     * @Route("/{id}/edit", name="professionnel_offre_edit", methods="GET|POST")
     */
    public function edit(Request $request, Offre $offre): Response
    {
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('professionnel_offre_edit', ['id' => $offre->getId()]);
        }

        return $this->render('professionneloffre/edit.html.twig', [
            'offre' => $offre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="professionnel_offre_delete", methods="DELETE")
     */
    public function delete(Request $request, Offre $offre): Response
    {
        if ($this->isCsrfTokenValid('delete'.$offre->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($offre);
            $em->flush();
        }

        return $this->redirectToRoute('professionnel_gestion');
    }
}
