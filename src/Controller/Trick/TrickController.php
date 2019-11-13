<?php

namespace App\Controller\Trick;

use App\Entity\Trick;
use App\Form\Trick\TrickType;
use App\Service\TrickManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/trick")
 */
class TrickController extends AbstractController
{

    /**
     * @Route("/new", name="trick_new", methods={"GET","POST"})
     * @param Request $request
     * @param TrickManager $manager
     *
     * @return Response
     */
    public function newAction(Request $request, TrickManager $manager): Response
    {
        $trick=new Trick();
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($manager->update($trick)) {
                $this->addFlash('success', 'Création de la figure effectuée');

                return $this->redirectToRoute('home');
            }
            $this->addFlash('danger', 'La création a echoué. En voici les raisons : ' . $manager->getError($trick));
        }

        return $this->render('trick/new.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }
}