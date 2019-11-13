<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Form\User\UserEditType;
use App\Form\User\UserType;
use App\Repository\UserRepository;
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
     * @Route("/{id}", name="user_show", methods={"GET"})
     *
     * @param User $user
     * @return Response
     */
    public function showAction(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }
  
  
    /**
     * @Route("/", name="user_index", methods={"GET"})
     *
     * @param UserRepository $userRepository
     * @return Response
     */
    public function indexAction(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }      
          

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     * @param Request $request
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
            $this->addFlash('danger', 'La création a echoué. En voici les raisons : ' . $manager->getErrors($user));
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
          
     /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     * @param Request $request
     * @param UserManager $manager
     * @param User $user
     *
     * @return Response
     */
    public function editAction(Request $request,User $user, UserManager $manager): Response
    {
        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($manager->update($user)) {
                $this->addFlash('success', 'Modification de l\'utilisateur effectuée');

                return $this->redirectToRoute('user_index');
            }
            $this->addFlash('danger', 'La modification a echoué. En voici les raisons : ' . $manager->getErrors($user));
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
