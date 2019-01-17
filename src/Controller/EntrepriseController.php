<?php

namespace App\Controller;

use App\Entity\Creneaux;
use App\Entity\Entreprise;
use App\Form\EntrepriseType;
use App\Repository\CreneauxRepository;
use App\Repository\EntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

// Include PhpSpreadsheet required namespaces
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * @Route("/administration/entreprise")
 */
class EntrepriseController extends AbstractController
{
    /**
     * @Route("/", name="entreprise_index", methods="GET")
     * @param EntrepriseRepository $entrepriseRepository
     *
     * @return Response
     */
    public function index(EntrepriseRepository $entrepriseRepository): Response
    {
        return $this->render('entreprise/index.html.twig', ['entreprises' => $entrepriseRepository->findAll()]);
    }

    /**
     * @Route("/stand", name="entreprise_stand", methods="GET|POST")
     *
     *
     * @return Response
     */
    public function stand(EntityManagerInterface $entityManager, EntrepriseRepository $entrepriseRepository, Request $request): Response
    {
        $entreprises = $entrepriseRepository->findBy([], ['societe' => 'asc']);

        if ($request->isMethod('POST')) {
            //formulaire soumis

            /** @var Entreprise $entreprise */
            foreach ($entreprises as $entreprise) {
                $stand = $request->request->get('num_stand_'.$entreprise->getId());
                $salle = $request->request->get('salle_'.$entreprise->getId());
                if (isset($stand)) {
                    $entreprise->setNumerostand($stand);
                }

                if (isset($salle)) {
                    $entreprise->setSalle($salle);
                }

                $entityManager->persist($entreprise);
            }
            $entityManager->flush();
        }

        return $this->render('entreprise/stand.html.twig', ['entreprises' => $entreprises]);
    }

    /**
     * @param EntrepriseRepository $entrepriseRepository
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/export", name="entreprise_export", methods="GET")
     */
    public function export(EntrepriseRepository $entrepriseRepository): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Entreprise');
        $sheet->setCellValue('B1', 'Adresse');
        $sheet->setCellValue('C1', 'CP');
        $sheet->setCellValue('D1', 'Ville');
        $sheet->setCellValue('E1', 'presentationEntreprise');
        $sheet->setCellValue('F1', 'jobdating');
        $sheet->setCellValue('G1', 'potcloture');
        $sheet->setCellValue('H1', 'nb');
        $sheet->setCellValue('I1', 'prise');
        $sheet->setCellValue('J1', 'tables');
        $sheet->setCellValue('K1', 'chaises');
        $sheet->setCellValue('L1', 'stand');
        $sheet->setCellValue('M1', 'salle');
        $sheet->setTitle('Liste des Entreprises');

        $entreprises = $entrepriseRepository->findAll();

        $row = 2;
        foreach ($entreprises as $entreprise) {
            $sheet->setCellValueByColumnAndRow(1, $row, $entreprise->getSociete());
            $sheet->setCellValueByColumnAndRow(2, $row, $entreprise->getAdresse());
            $sheet->setCellValueByColumnAndRow(3, $row, $entreprise->getCp());
            $sheet->setCellValueByColumnAndRow(4, $row, $entreprise->getVille());
            $sheet->setCellValueByColumnAndRow(5, $row, $entreprise->getPresentationEntreprise());
            $sheet->setCellValueByColumnAndRow(6, $row, $entreprise->getJobdating());
            $sheet->setCellValueByColumnAndRow(7, $row, $entreprise->getPotcloture());
            $sheet->setCellValueByColumnAndRow(8, $row, count($entreprise->getRepresentants()));
            $sheet->setCellValueByColumnAndRow(9, $row, $entreprise->getPrise());
            $sheet->setCellValueByColumnAndRow(10, $row, $entreprise->getNbtables());
            $sheet->setCellValueByColumnAndRow(11, $row, $entreprise->getNbchaises());
            $sheet->setCellValueByColumnAndRow(12, $row, $entreprise->getNumerostand());
            $sheet->setCellValueByColumnAndRow(13, $row, $entreprise->getSalle());

            $row++;
        }

        // Create your Office 2007 Excel (XLSX Format)
        $writer = new Xlsx($spreadsheet);
        $datejour = new \DateTime('now');
        // Create a Temporary file in the system
        $fileName = 'liste_entreprise-' . $datejour->format('d-m-Y-H-i') . '.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        // Create the excel file in the tmp directory of the system
        $writer->save($temp_file);

        // Return the excel file as an attachment
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);

    }

    /**
     * @Route("/new", name="entreprise_new", methods="GET|POST")
     * @param Request $request
     *
     * @return Response
     */
    public function new(Request $request): Response
    {
        $entreprise = new Entreprise();
        $form = $this->createForm(EntrepriseType::class, $entreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entreprise);
            $em->flush();

            return $this->redirectToRoute('entreprise_index');
        }

        return $this->render('entreprise/new.html.twig', [
            'entreprise' => $entreprise,
            'form'       => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="entreprise_show", methods="GET")
     * @param Entreprise $entreprise
     *
     * @return Response
     */
    public function show(Entreprise $entreprise): Response
    {
        return $this->render('entreprise/show.html.twig', ['entreprise' => $entreprise]);
    }

    /**
     * @Route("/planning/{id}", name="entreprise_planning", methods="GET")
     * @param CreneauxRepository $creneauxRepository
     * @param Entreprise         $entreprise
     *
     * @return Response
     */
    public function planning(CreneauxRepository $creneauxRepository, Entreprise $entreprise): Response
    {
        //todo: voir les créneaux de l'entreprise / offre ou un seul créneau par entreprise
        return $this->render('entreprise/planning.html.twig', [
            'entreprise' => $entreprise,
            'creneaux'   => Creneaux::TAB_CRENEAUX,
            'occupation' => $creneauxRepository->findByEntreprise($entreprise)
            ]);
    }

    /**
     * @Route("/ajax/indisponible", name="ajax_entreprise_indisponible", methods="POST")
     */
    public function ajaxIndisponible(
        EntrepriseRepository $entrepriseRepository,
        CreneauxRepository $creneauxRepository,
        EntityManagerInterface $entityManager,
        Request $request) {
        $entreprise = $entrepriseRepository->find($request->request->get('entreprise'));
        $cr = $creneauxRepository->findBy(['heure' => $request->request->get('cr'), 'entreprise' => $entreprise->getId()]);

        if ($entreprise) {
            if (count($cr) === 1) {
                $cr[0]->setIndisponible($request->request->get('value'));
                $entityManager->flush();
            } else if (count($cr) === 0) {
                $cr = new Creneaux();
                $cr->setEntreprise($entreprise);
                $cr->setHeure($request->request->get('cr'));
                $cr->setIndisponible($request->request->get('value'));
                $entityManager->persist($cr);
                $entityManager->flush();
            }

            return new Response(Response::HTTP_OK);
        }
        return new Response(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @Route("/{id}/edit", name="entreprise_edit", methods="GET|POST")
     * @param Request    $request
     * @param Entreprise $entreprise
     *
     * @return Response
     */
    public function edit(Request $request, Entreprise $entreprise): Response
    {
        $form = $this->createForm(EntrepriseType::class, $entreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('entreprise_edit', ['id' => $entreprise->getId()]);
        }

        return $this->render('entreprise/edit.html.twig', [
            'entreprise' => $entreprise,
            'form'       => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="entreprise_delete", methods="DELETE")
     * @param Request    $request
     * @param Entreprise $entreprise
     *
     * @return Response
     */
    public function delete(Request $request, Entreprise $entreprise): Response
    {
        if ($this->isCsrfTokenValid('delete' . $entreprise->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($entreprise);
            $em->flush();
        }

        return $this->redirectToRoute('entreprise_index');
    }


}
