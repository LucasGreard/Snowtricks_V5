<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Figures;
use App\Repository\CommentsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentsController extends AbstractController
{
    /**
     * @Route("/figure/id/deleteCom/{id}", name="comment_delete")
     */
    public function deleteComment(Figures $figure, Request $request, CommentsRepository $commentRepo)
    {
        return true;
    }
}
