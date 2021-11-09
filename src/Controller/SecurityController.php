<?php

namespace App\Controller;

use App\Repository\USerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Route("/auth")
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils, USerRepository $userRepo): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('figures_index');
        }
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
    private function isValid(USerRepository $userRepo, $lastUsername)
    {
        $userValid = $userRepo->findBy([
            'activate' => null,
            'userEmail' => $lastUsername
        ]);

        // return $userValid ? true : false;
        if ($userValid) {
            return true;
        } else {
            return false;
        }
    }
}
