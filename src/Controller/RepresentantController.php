<?php

namespace App\Controller;

use App\Entity\Representant;
use App\Form\RepresentantType;
use App\Repository\RepresentantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

// Include PhpSpreadsheet required namespaces
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * @Route("/administration/representant")
 */
class RepresentantController extends AbstractController
{
    /**
     * @Route("/", name="representant_index", methods="GET")
     */
    public function index(RepresentantRepository $representantRepository): Response
    {
        return $this->render('representant/index.html.twig', ['representants' => $representantRepository->findAll()]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/export", name="representant_export", methods="GET")
     */
    public function export(RepresentantRepository $representantRepository) {
        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Civilité');
        $sheet->setCellValue('B1', 'Nom');
        $sheet->setCellValue('C1', 'Prenom');
        $sheet->setCellValue('D1', 'email');
        $sheet->setCellValue('E1', 'telephone');
        $sheet->setCellValue('F1', 'entreprise');
        $sheet->setCellValue('G1', 'stand');
        $sheet->setCellValue('H1', 'salle');
        $sheet->setTitle("Liste des inscrits");

        $representants = $representantRepository->findAll();

        $row = 2;
        foreach ($representants as $representant) {
            $sheet->setCellValueByColumnAndRow(1, $row, $representant->getCivilite());
            $sheet->setCellValueByColumnAndRow(2, $row, $representant->getNom());
            $sheet->setCellValueByColumnAndRow(3, $row, $representant->getPrenom());
            $sheet->setCellValueByColumnAndRow(4, $row, $representant->getEmail());
            $sheet->setCellValueByColumnAndRow(5, $row, $representant->getTelephone());
            if ($representant->getEntreprise() !== null) {
                $sheet->setCellValueByColumnAndRow(6, $row, $representant->getEntreprise()->getSociete());
                $sheet->setCellValueByColumnAndRow(7, $row, $representant->getEntreprise()->getNumerostand());
                $sheet->setCellValueByColumnAndRow(8, $row, $representant->getEntreprise()->getSalle());
            }

            $row++;
        }

        // Create your Office 2007 Excel (XLSX Format)
        $writer = new Xlsx($spreadsheet);
        $datejour = new \DateTime('now');
        // Create a Temporary file in the system
        $fileName = 'liste_representant-'.$datejour->format('d-m-Y-H-i').'.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        // Create the excel file in the tmp directory of the system
        $writer->save($temp_file);

        // Return the excel file as an attachment
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);

    }

    /**
     * @Route("/new", name="representant_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $representant = new Representant();
        $form = $this->createForm(RepresentantType::class, $representant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($representant);
            $em->flush();

            return $this->redirectToRoute('representant_index');
        }

        return $this->render('representant/new.html.twig', [
            'representant' => $representant,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="representant_show", methods="GET")
     */
    public function show(Representant $representant): Response
    {
        return $this->render('representant/show.html.twig', ['representant' => $representant]);
    }

    /**
     * @Route("/{id}/edit", name="representant_edit", methods="GET|POST")
     */
    public function edit(Request $request, Representant $representant): Response
    {
        $form = $this->createForm(RepresentantType::class, $representant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('representant_edit', ['id' => $representant->getId()]);
        }

        return $this->render('representant/edit.html.twig', [
            'representant' => $representant,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="representant_delete", methods="DELETE")
     */
    public function delete(Request $request, Representant $representant): Response
    {
        if ($this->isCsrfTokenValid('delete'.$representant->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($representant);
            $em->flush();
        }

        return $this->redirectToRoute('representant_index');
    }
}
