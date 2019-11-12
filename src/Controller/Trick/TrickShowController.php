<?php

namespace App\Controller\Trick;

use App\Entity\Trick;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/trick")
 */
class TrickShowController extends AbstractController
{

    /**
     * @Route("/{slug}", name="trick_show", methods={"GET"})
     *
     * @param Trick $trick
     * @return Response
     */
    public function showAction(Trick $trick): Response
    {
        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
        ]);
    }
}