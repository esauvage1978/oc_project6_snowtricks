<?php

namespace App\Controller\Category;

use App\Entity\Category;
use App\Form\Category\CategoryType;
use App\Repository\CategoryRepository;
use App\Service\CategoryManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/category")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="category_index", methods={"GET"})
     *
     * @param CategoryRepository $categoryRepository
     * @return Response
     */
    public function indexAction(CategoryRepository $categoryRepository): Response
    {
        return $this->render('category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="category_new", methods={"GET","POST"})
     *
     * @param Request $request
     * @param CategoryManager $categoryManager
     * @return Response
     */
    public function newAction(Request $request, CategoryManager $categoryManager): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($categoryManager->update($category)) {
                $this->addFlash('success', 'Création de la catégorie effectuée');
                return $this->redirectToRoute('category_index');
            }

            $this->addFlash('danger', 'La création a echoué. En voici les raisons : ' . $categoryManager->getError($category));
        }

        return $this->render('category/new.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="category_show", methods={"GET"})
     *
     * @param Category $category
     * @return Response
     */
    public function showAction(Category $category): Response
    {
        return $this->render('category/show.html.twig', [
            'category' => $category,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="category_edit", methods={"GET","POST"})
     *
     * @param Request $request
     * @param Category $category
     * @param CategoryManager $categoryManager
     * @return Response
     */
    public function editAction(Request $request, Category $category, CategoryManager $categoryManager): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($categoryManager->update($category)) {
                $this->addFlash('success', 'Modification de la catégorie effectuée');
                return $this->redirectToRoute('category_index');
            }
            $this->addFlash('danger', 'La création a echoué. En voici les raisons : ' . $categoryManager->getError($category));

        }

        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="category_delete", methods={"DELETE"})
     *
     * @param Request $request
     * @param Category $category
     * @param CategoryManager $categoryManager
     * @return Response
     */
    public function deleteAction(Request $request, Category $category, CategoryManager $categoryManager ): Response
    {
        if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->request->get('_token'))) {

            $this->addFlash('success', 'La catégorie est supprimée');
            $categoryManager->remove($category);

        }

        return $this->redirectToRoute('category_index');
    }
}
