<?php

namespace App\Controller;

use App\Entity\USer;
use App\Form\ManageUsersType;
use App\Repository\USerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ManageUsersController extends AbstractController
{
    /**
     * @Route("/admin/users", name="users_manage")
     */
    public function index(USerRepository $userRepo, Request $request): Response
    {
        $form = $this->createForm(ManageUsersType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        }
        return $this->render('admin/usersManage.html.twig', [
            'controller_name' => 'ManageUsersController',
            'users' => $userRepo->findAll()
        ]);
    }
    /**
     * @Route("/admin/users/{id}", name="user_delete", methods={"POST"})
     */
    public function delete(USer $user): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('users_manage', [], Response::HTTP_SEE_OTHER);
    }
}
