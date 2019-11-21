<?php

namespace App\Controller\Trick;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Form\Trick\CommentType;
use App\Form\Trick\TrickType;
use App\Repository\CommentRepository;
use App\Security\TrickVoter;
use App\Service\CommentManager;
use App\Service\CommentPaginator;
use App\Service\TrickManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
     * @Route("/{slug}/edit", name="trick_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Trick $trick
     * @param TrickManager $manager
     *
     * @return Response
     */
    public function editAction(Request $request,Trick $trick, TrickManager $manager): Response
    {
        $this->denyAccessUnlessGranted(TrickVoter::UPDATE, $trick);

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

    /**
     * @Route("/new", name="trick_new", methods={"GET","POST"})
     * @param Request $request
     * @param TrickManager $manager
     *
     * @return Response
     * @isGranted("ROLE_USER")
     */
    public function newAction(Request $request, TrickManager $manager): Response
    {
        $this->denyAccessUnlessGranted(TrickVoter::CREATE, null);

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
     * @Route("/{slug}/{page?<\d+>1}", name="trick_show", methods={"GET","POST"})
     *
     * @param Request $request
     * @param Trick $trick
     * @param CommentManager $commentManager
     * @param CommentPaginator $commentPaginator
     * @param $page
     * @return Response
     */
    public function showAction(
        Request $request,
        Trick $trick,
        CommentManager $commentManager,
        CommentPaginator $commentPaginator,
        $page = 1
    ): Response
    {

        $commentPaginator->initialise($trick);

        $page=$commentPaginator->checkPage($page);

        $nbrPages=$commentPaginator->getNbrSheets();

        if ($page > $nbrPages ) {
            $page = $nbrPages;
        }

        $comments=$commentPaginator->getCommentsForPage($page);

        $comment = new Comment();
        $formComment = $this->createForm(CommentType::class, $comment);
        $formComment->handleRequest($request);

        if ($formComment->isSubmitted() && $formComment->isValid()) {

            if ($commentManager->update($comment,$trick, $this->getUser())) {
                $this->addFlash('success', 'Commentaire ajouté');

                return $this->redirectToRoute('trick_show', [
                        'slug' => $trick->getSlug(),
                    ]
                );
            }
            $this->addFlash('danger', 'Erreur lors de l\'ajout du commentaire : ' . $commentManager->getErrors($comment));
        }

        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
            'form' => $formComment->createView(),
            'comments' => $comments,
            'nbrPages' => $nbrPages,
            'currentPage' => $page,
        ]);
    }

    /**
     * @Route("/{id}", name="trick_delete", methods={"DELETE"})
     *
     * @param Request $request
     * @param Trick $trick
     * @param TrickManager $trickManager
     * @return Response
     */
    public function deleteAction(Request $request, Trick $trick, TrickManager $trickManager ): Response
    {
        $this->denyAccessUnlessGranted(TrickVoter::DELETE, $trick);

        if ($this->isCsrfTokenValid('delete' . $trick->getId(), $request->request->get('_token'))) {

            $this->addFlash('success', 'La figure est supprimée');
            $trickManager->remove($trick);
        }

        return $this->redirectToRoute('home');
    }
}