<?php

namespace App\Controller;

use App\Entity\USer;
use App\Form\RegistrationFormType;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/auth")
 */
class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_regi ster")
     */
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasherInterface): Response
    {

        $user = new USer();
        $user->setUserDateAdd(new DateTime('now'));
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPassword(
                $userPasswordHasherInterface->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $userPicture = $form->get('userPicture')->getData();
            if ($userPicture) {
                $nameImg = md5(uniqid()) . '.' . $userPicture->guessExtension();

                $userPicture->move(
                    $this->getParameter('images_directory_user'),
                    $nameImg
                );
                $user->setUserPicture($nameImg);
            } else {
                $nameImg = "../_default/userAvatar.png";
                $user->setUserPicture($nameImg);
            }


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();


            return $this->redirectToRoute('figures_index');
        }
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
