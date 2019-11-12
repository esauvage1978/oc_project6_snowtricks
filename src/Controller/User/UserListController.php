<?php


namespace App\Controller\User;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserListController extends AbstractController
{
    /**
     * @Route("/user/", name="user_index", methods={"GET"})
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
}