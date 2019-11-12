<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    /**
     * @Route("/", name="home", methods={"GET"})
     * @param TrickRepository $trickRepository
     *
     * @return Response
     */
    public function homeAction( TrickRepository $trickRepository): Response
    {
        return $this->render('home.html.twig', [
            'tricks' => $trickRepository->findAll(),
        ]);
    }
}