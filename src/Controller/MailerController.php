<?php

namespace App\Controller;

use App\Entity\USer;
use App\Form\ForgotPasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Flex\Path;

class MailerController extends AbstractController
{
    /**
     * @Route("/mailer", name="mailer")
     */
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $user = new USer();
        $form = $this->createForm(ForgotPasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $userEmail = $request->request->get('forgot_password');
            //Savoir si l'utilateur existe en bdd
            
            dd($userEmail);

            // $this->sendEmail($userEmail['userEmail'], $mailer);
        }


        return $this->render('mailer/index.html.twig', [
            'forgotPassword' => $form->createView(),
        ]);
    }

    private function sendEmail($userEmail, MailerInterface $mailer)
    {
        $email = (new Email())
            ->from('mestestemailoc@gmail.com')
            ->to($userEmail)
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        $mailer->send($email);
    }
}
