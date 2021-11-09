<?php

namespace App\Controller;

use App\Entity\USer;
use App\Entity\UserNewPW;
use App\Form\ForgotPasswordType;
use App\Repository\USerRepository;
use DateTimeImmutable;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;

class MailerController extends AbstractController
{
    private $flash;
    public function __construct(FlashBagInterface $flash)
    {
        $this->flash = $flash;
    }
    /**
     * @Route("/mailer", name="mailer")
     */
    public function index(Request $request, MailerInterface $mailer, USerRepository $userRepo): Response
    {
        $user = new USer();
        $newMdp = new UserNewPW();
        $form = $this->createForm(ForgotPasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userEmail = $request->request->get('forgot_password');

            $ifExist = $userRepo->findOneBy([
                'userEmail' => $userEmail['userEmail']
            ]);
            if ($ifExist) {
                $newMdp->setToken(md5(uniqid()));
                $_token = $newMdp->getToken();
                $newMdp->setCreatedAt(new DateTimeImmutable('now'));
                $newMdp->setUser($ifExist);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($newMdp);
                $entityManager->flush();

                $this->sendEmail($userEmail['userEmail'], $mailer, $_token);

                $this->flash->add('success', 'Un mail vous a été envoyé !');

                return $this->redirectToRoute('mailer');
            } else {
                $this->flash->add('success', 'Aucun compte existant');
                return $this->redirectToRoute('mailer');
            }
        }
        return $this->render('mailer/index.html.twig', [
            'forgotPassword' => $form->createView(),
        ]);
    }

    private function sendEmail($userEmail, $mailer, $_token)
    {
        $email = (new TemplatedEmail())
            ->from(new Address('infos@gmail.com'))
            ->to($userEmail)
            ->subject('Lien de changement de votre mot de passe')
            ->htmlTemplate('mailer/newPassword.html.twig')
            ->context([
                'token' => $_token
            ]);
        $mailer->send($email);
    }
}
