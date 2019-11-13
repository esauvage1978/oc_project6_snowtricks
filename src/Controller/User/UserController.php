<?php

namespace App\Controller\Category;

use App\Entity\User;
use App\Service\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     *
     * @param Request $request
     * @param User $user
     * @param UserManager $categoryManager
     * @return Response
     */
    public function deleteAction(Request $request, User $user, UserManager $userManager ): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {

            $this->addFlash('success', 'L\{utilisateur est supprimé');
            $userManager->remove($user);
        }

        return $this->redirectToRoute('user_index');
    }
}
