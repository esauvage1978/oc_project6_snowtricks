<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Form\User\UserType;
use App\Service\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/user")
 */
class UserController extends AbstractController
{

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     * @param Request $request
     * @param User $user
     * @param UserManager $manager
     *
     * @return Response
     */
    public function newAction(Request $request, UserManager $manager): Response
    {
        $user=new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($manager->update($user)) {
                $this->addFlash('success', 'Création de l\'utilisateur effectuée');

                return $this->redirectToRoute('home');
            }
            $this->addFlash('danger', 'La création a echoué. En voici les raisons : ' . $manager->getError($user));
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}