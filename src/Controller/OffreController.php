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
 * @Route("/administration/offre")
 */
class OffreController extends AbstractController
{
    /**
     * @Route("/", name="offre_index", methods="GET")
     */
    public function index(OffreRepository $offreRepository): Response
    {
        return $this->render('offre/index.html.twig', ['offres' => $offreRepository->findAll()]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/export", name="offre_export", methods="GET")
     */
    public function export(OffreRepository $offreRepository) {
        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Titre');
        $sheet->setCellValue('B1', 'Entreprise');
        $sheet->setCellValue('C1', 'Diplôme');

        $sheet->setTitle("Liste des offres");

        $offres = $offreRepository->findAll();

        $row = 2;
        foreach ($offres as $offre) {
            $sheet->setCellValueByColumnAndRow(1, $row, $offre->getTitre());
            if ($offre->getEntreprise() !== null)
                $sheet->setCellValueByColumnAndRow(2, $row, $offre->getEntreprise()->getSociete());
            $col = 3;
            foreach ($offre->getDiplomes() as $diplome) {
                $sheet->setCellValueByColumnAndRow($col, $row, $diplome->getSigle());
                $col++;
            }
            $row++;
        }

        // Create your Office 2007 Excel (XLSX Format)
        $writer = new Xlsx($spreadsheet);
        $datejour = new \DateTime('now');
        // Create a Temporary file in the system
        $fileName = 'liste_offre-'.$datejour->format('d-m-Y-H-i').'.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        // Create the excel file in the tmp directory of the system
        $writer->save($temp_file);

        // Return the excel file as an attachment
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);

    }

    /**
     * @Route("/new/{entreprise}", name="offre_new", methods="GET|POST")
     */
    public function new(Request $request, Entreprise $entreprise = null): Response
    {
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($offre);
            $em->flush();

            return $this->redirectToRoute('offre_index');
        }

        return $this->render('offre/new.html.twig', [
            'offre' => $offre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="offre_show", methods="GET")
     */
    public function show(Offre $offre): Response
    {
        return $this->render('offre/show.html.twig', ['offre' => $offre]);
    }

    /**
     * @Route("/{id}/edit", name="offre_edit", methods="GET|POST")
     */
    public function edit(Request $request, Offre $offre): Response
    {
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('offre_edit', ['id' => $offre->getId()]);
        }

        return $this->render('offre/edit.html.twig', [
            'offre' => $offre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="offre_delete", methods="DELETE")
     */
    public function delete(Request $request, Offre $offre): Response
    {
        if ($this->isCsrfTokenValid('delete'.$offre->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($offre);
            $em->flush();
        }

        return $this->redirectToRoute('offre_index');
    }
}
