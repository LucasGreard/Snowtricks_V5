<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\FigureImg;
use App\Entity\Figures;
use App\Form\CommentsType;
use App\Form\FiguresType;
use App\Repository\FiguresRepository;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

/**
 * @Route("/")
 */
class FiguresController extends AbstractController
{
    private $flash;
    public function __construct(FlashBagInterface $flash)
    {
        $this->flash = $flash;
    }

    /**
     * @Route("/", name="figures_index", methods={"GET"})
     */
    public function index(FiguresRepository $figuresRepository): Response
    {
        return $this->render('figures/index.html.twig', [
            'figures' => $figuresRepository->findAll(),
        ]);
    }

    /**
     * @Route("/figure/new", name="figures_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $figure = new Figures();
        $form = $this->createForm(FiguresType::class, $figure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $figureDateAdd = $this->addDateTimeNow();
            $figure->setFigureDateAdd($figureDateAdd);

            $figuresImg = $form->get('figureImg')->getData();

            $this->recoverImg($figuresImg, $figure);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($figure);
            $entityManager->flush();
            $this->flash->add('success', 'Votre figure a bien été ajouté');

            return $this->redirectToRoute('figures_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('figures/new.html.twig', [
            'figure' => $figure,
            'form' => $form,
        ]);
    }
    /**
     * @Route("/figure/delete/img/{id}", name="figure_deleteImg", methods={"DELETE"})
     */
    public function deleteFigureImg(FigureImg $figureImg, Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if ($this->isCsrfTokenValid('delete' . $figureImg->getId(), $data['_token'])) {
            $nom = $figureImg->getImgName();
            unlink($this->getParameter('images_directory') . '/' . $nom);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($figureImg);
            $entityManager->flush();
            $this->flash->add('success', 'Votre image a bien été supprimé');
            return new JsonResponse(['success' => 1]);
        }
        return new JsonResponse(['error' => 'Token Invalid'], 400);
    }
    /**
     * @Route("/figure/id/{id}", name="figures_show", methods={"GET", "POST"})
     */
    public function show(Figures $figure, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $comments = $em->getRepository("App\Entity\Comments")->findAll();
        
        if ($this->getUser()) {
            $user = $this->getUser();
            $userName = sprintf("%s %s", $user->getUserLastName(), $user->getUserFirstName());


            $comment = new Comments();
            $commentForm = $this->createForm(CommentsType::class, $comment);
            $commentForm->handleRequest($request);
            if ($commentForm->isSubmitted() && $commentForm->isValid()) {
                $this->addComment($comment, $userName, $figure, $user);
            }
            return $this->render('figures/show.html.twig', [
                'figure' => $figure,
                'commentForm' => $commentForm->createView(),
                'comments' => $comments
            ]);
        }

        return $this->render('figures/show.html.twig', [
            'figure' => $figure,
            'comments' => $comments
        ]);
    }

    /**
     * @Route("/figure/{id}/edit", name="figures_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Figures $figure)
    {
        $form = $this->createForm(FiguresType::class, $figure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $figuresImg = $form->get('figureImg')->getData();
            foreach ($figuresImg as $figureImg) {
                $nameImg = md5(uniqid()) . '.' . $figureImg->guessExtension();

                $figureImg->move(
                    $this->getParameter('images_directory'),
                    $nameImg
                );

                $img = new FigureImg();
                $img->setImgName($nameImg);

                $figure->addFigureImg($img);
            }
            $this->flash->add('success', 'Votre figure a bien été modifié');
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('figures_show', ['id' => $figure->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('figures/edit.html.twig', [
            'figure' => $figure,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/figure/{id}", name="figures_delete", methods={"POST"})
     */
    public function delete(Request $request, Figures $figure): Response
    {
        if ($this->isCsrfTokenValid('delete' . $figure->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($figure);
            $entityManager->flush();
        }

        return $this->redirectToRoute('figures_index', [], Response::HTTP_SEE_OTHER);
    }

    private function addDateTimeNow()
    {
        return new \DateTime();
    }

    private function recoverImg($figuresImg, $figure)
    {
        $img = new FigureImg();
        if ($figuresImg) {
            foreach ($figuresImg as $figureImg) {
                $nameImg = md5(uniqid()) . '.' . $figureImg->guessExtension();

                $figureImg->move(
                    $this->getParameter('images_directory'),
                    $nameImg
                );


                $img->setImgName($nameImg);
                $figure->addFigureImg($img);
            }
        } else {
            $nameImg = "../_default/figure_Default.jpg";
            $img->setImgName($nameImg);
            $figure->addFigureImg($img);
        }
    }
    private function addComment($comment, $userName, $figure, $user)
    {
        $comment->setCreatedAt(new DateTimeImmutable());
        $comment->setAuthor($userName);
        $comment->setFigure($figure);
        $comment->setUserPicture($user->getUserPicture());
        $comment->setUser($user);

        $em = $this->getDoctrine()->getManager();
        $em->persist($comment);
        $em->flush();
    }
}
