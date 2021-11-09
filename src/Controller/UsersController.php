<?php

namespace App\Controller;

use App\Entity\USer as EntityUSer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/users", name="users_")
 */
class UsersController extends AbstractController
{
    /**
     * @Route("/", name="setting", methods={"GET","POST"})
     */
    public function index()
    {
        return $this->render('users/index.html.twig');
    }

    /**
     * @Route("/edit", name="edit", methods={"POST"})
     */
    public function edit(Request $request, UserInterface $user)
    {
        $imgNewName = $this->recoverImg($request);
        if ($imgNewName) {
            $userId = $user->getUserIdentifier();
            $entityManager = $this->getDoctrine()->getManager();
            $userImg = $entityManager->getRepository(EntityUSer::class)->findOneBy(['userEmail' => $userId]);

            $this->unlinkImg($userImg);

            $userImg->setUserPicture($imgNewName);

            $entityManager->flush();
        }
        return $this->redirectToRoute('users_setting');
    }

    private function recoverImg(Request $request)
    {
        $userNewPicture = $request->files->get('userImgToUpload');
        if ($userNewPicture) {
            $imgChangeName = md5(uniqid()) . '.' . $userNewPicture->guessExtension();
            $userNewPicture->move(
                $this->getParameter('images_directory_user'),
                $imgChangeName
            );
            return $imgChangeName;
        } else {
            return null;
        }
    }
    private function unlinkImg($userImg)
    {
        $userLastPicture = $userImg->getUserPicture();

        if ($userLastPicture != "../_default/userAvatar.png") {
            unlink($this->getParameter('images_directory_user') . '/' . $userLastPicture);
        } else {
            return null;
        }
    }
}
