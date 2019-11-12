<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Form\User\UserEditType;
use App\Service\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 */
class UserModifyController extends AbstractController
{

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     * @param Request $request
     * @param UserManager $manager
     * @param User $user
     *
     * @return Response
     */
    public function newAction(Request $request,User $user, UserManager $manager): Response
    {
        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($manager->update($user)) {
                $this->addFlash('success', 'Modification de l\'utilisateur effectuée');

                return $this->redirectToRoute('user_index');
            }
            $this->addFlash('danger', 'La modification a echoué. En voici les raisons : ' . $manager->getError($user));
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}