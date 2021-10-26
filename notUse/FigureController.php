<?php

namespace App\Controller;

use App\Service\FileUploader;
use App\Entity\Figure;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\AddFigureFormType;
use Symfony\Component\HttpFoundation\File\UploadedFile;



class FigureController extends AbstractController
{

    /**
     * @Route("/", name="figure")
     */
    public function index(): Response
    {
        $figure = $this->getDoctrine()->getRepository(Figure::class)->findAll();
        return $this->render('figure/allFigures.html.twig', [
            'figures' => $figure
        ]);
    }
    /**
     * @Route("/addFigure", name="figure")
     */
    public function addFigure(Request $request, FileUploader $fileUploader)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $figure = new Figure();
        $figure->setFigureDateAdd(new \DateTime('now'));
        $form = $this->createForm(AddFigureFormType::class, $figure);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            /** 
             * @var UploadedFile $figurePicture 
             * */
            $figurePicture = $form->get('figure_Picture')->getData();
            dump($figurePicture);
            $figureFileName = $fileUploader->upload($figurePicture);
            $figure->setFigurePicture($figureFileName);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($figure);
            $entityManager->flush();
            $figure = $this->getDoctrine()->getRepository(Figure::class)->findAll();

            return $this->render('figure/allFigures.html.twig', [
                'figures' => $figure
            ]);
        }
        return $this->render('figure/addFigure.html.twig', [
            'addFigureForm' => $form->createView(),
        ]);
    }
    /**
     * @Route("/figure/delete/{id}")
     */
    public function deleteFigure(int $id)
    {

        $entityManager = $this->getDoctrine()->getManager();
        $figure = $entityManager->getRepository(Figure::class)->find($id);
        $image = $figure->getFigurePicture();
        if ($image) {
            $nomImage = $this->getParameter('figure_img') . '/' . $image->getFigurePicture();
            if (file_exists($nomImage)) {
                unlink($nomImage);
            }
        }
        $entityManager->remove($figure);
        $entityManager->flush();
        return $this->index();
    }
    /**
     * @Route("/figure/{id}")
     */
    public function showOneFigure(int $id)
    {
        $figure = $this->getDoctrine()->getRepository(Figure::class)->find($id);
        return $this->render('figure/oneFigure.html.twig', [
            'figure' => $figure
        ]);
    }

    private function _dansLeIf()
    {
    }
}
