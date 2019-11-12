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
class TrickModifyController extends AbstractController
{

    /**
     * @Route("/{slug}/edit", name="trick_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Trick $trick
     * @param TrickManager $manager
     *
     * @return Response
     */
    public function newAction(Request $request,Trick $trick, TrickManager $manager): Response
    {
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($manager->update($trick)) {
                $this->addFlash('success', 'Modification de la figure effectuée');

                return $this->redirectToRoute('home');
            }
            $this->addFlash('danger', 'La modification a echoué. En voici les raisons : ' . $manager->getError($trick));
        }

        return $this->render('trick/edit.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }
}