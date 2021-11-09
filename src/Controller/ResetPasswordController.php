<?php

namespace App\Controller;

use App\Entity\USer as EntityUSer;
use App\Form\NewPasswordType;
use App\Repository\UserNewPWRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
class ResetPasswordController extends AbstractController
{
    private $flash;
    public function __construct(FlashBagInterface $flash)
    {
        $this->flash = $flash;
    }
    /**
     *@Route("/resetPassword/{token}", name="reset_password")
     */
    public function resetPassword($token, UserNewPWRepository $userRepo, Request $request, UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        //On verifie si un utilisateur a ce token
        $isValid = $userRepo->findOneBy(['_token' => $token]);
        $userId = $userRepo->findUserId($token);
        //Si aucun utilisateur n'existe avec ce token
        if (!$isValid) {
            //Erreur 404
            throw $this->createNotFoundException('Cet utilisateur n\'existe pas');
        }
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(EntityUSer::class)->find($userId[0][1]);

        $form = $this->createForm(NewPasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form->get('password')->getData();
            $password2 = $form->get('confirm_Password')->getData();
            if ($password == $password2) {
                $isValid->setToken(null);

                $user->setPassword(
                    $userPasswordHasherInterface->hashPassword(
                        $user,
                        $password
                    )
                );
                $entityManager->flush();
                $this->flash->add('success', 'Votre mot de passe a bien été changé !');
            } else {
                $this->flash->add('success', 'Les deux mots de passes ne sont pas identiques');
                return $this->redirectToRoute('reset_password', ['token' => $token]);
            }
            return $this->redirectToRoute('figures_index');
        }
        return $this->render('security/newPassword.html.twig', [
            'newPasswordForm' => $form->createView()
        ]);
    }
}
