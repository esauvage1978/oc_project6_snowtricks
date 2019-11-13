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

    /**
     * @Route("/new", name="trick_new", methods={"GET","POST"})
     * @param Request $request
     * @param TrickManager $manager
     *
     * @return Response
     */
    public function newAction(Request $request, TrickManager $manager): Response
    {
        $trick = new Trick();
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
          
      /**
     * @Route("/{slug}/edit", name="trick_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Trick $trick
     * @param TrickManager $manager
     *
     * @return Response
     */
    public function editAction(Request $request,Trick $trick, TrickManager $manager): Response
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