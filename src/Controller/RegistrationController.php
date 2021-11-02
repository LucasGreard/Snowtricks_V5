<?php

namespace App\Controller;

use App\Entity\USer;
use App\Form\RegistrationFormType;
use App\Repository\USerRepository;
use DateTime;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/auth")
 */
class RegistrationController extends AbstractController
{
    private $flash;
    public function __construct(FlashBagInterface $flash)
    {
        $this->flash = $flash;
    }
    /**
     * @Route("/register", name="app_regi ster")
     */
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasherInterface, MailerInterface $mailer, USerRepository $userRepo): Response
    {

        $user = new USer();
        $user->setUserDateAdd(new DateTime('now'));
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Hash password
            $user->setPassword(
                $userPasswordHasherInterface->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            //Add picture to server and bdd
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

            //Génération du token de validation
            $user->setActivate(md5(uniqid()));
            //On envoi le mail 
            $email = (new TemplatedEmail())
                ->from(new Address('infos@gmail.com'))
                ->to($form->get('userEmail')->getData())
                ->subject('Lien de validation de votre compte')

                // path of the Twig template to render
                ->htmlTemplate('mailer/validation.html.twig')

                // pass variables (name => value) to the template
                ->context([
                    'token' => $user->getActivate(),
                    'mail' => $form->get('userEmail')->getData()
                ]);

            $mailer->send($email);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->flash->add('success', 'Compte créé mais non-actif, vérifier votre boîte mail !');

            return $this->redirectToRoute('figures_index');
        }
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
    /**
     * @Route("/forgotPassword")
     */
    public function forgotPassword()
    {
        return $this->render('registration/forgotPassword.html.twig', []);
    }
    /**
     *@Route("/activation/{token}", name="activation")
     */
    public function activate($token, UserRepository $userRepo)
    {
        //On verifie si un utilisateur a ce token
        $user = $userRepo->findOneBy(['activate' => $token]);

        //Si aucun utilisateur n'existe avec ce token
        if (!$user) {
            //Erreur 404
            throw $this->createNotFoundException('Cet utilisateur n\'existe pas');
        }

        //On supprime le token
        $user->setActivate(null);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('success', 'Votre compte a bien été activé, connectez-vous !');

        return $this->redirectToRoute('figures_index');
    }
}
