<?php

namespace App\Controller\Profil;

use App\Form\Profil\PasswordChangeFormType;
use App\Form\Profil\ProfilType;
use App\Service\UserManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profil")
 * @IsGranted("ROLE_USER")
 */
class ProfilController extends AbstractController
{
    /**
     * @Route("/", name="profil_home", methods={"GET","POST"})
     *
     * @param UserManager $userManager
     * @param Request $request
     * @return Response
     */
    public function profilHomeAction(Request $request, UserManager $userManager): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfilType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($userManager->update($user)) {
                $this->addFlash('success', 'Modification effectué');
            } else {
                $this->addFlash('danger', 'Erreur lors de la modification : ' . $userManager->getErrors($user));
            }
        }

        return $this->render('profil/home.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/password-change", name="profil_password_change", methods={"GET","POST"})
     *
     * @param UserManager $userManager
     * @param Request $request
     * @return Response
     */
    public function changePasswordAction(Request $request, UserManager $userManager): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(PasswordChangeFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {

            $formData = $form->getData();

            if ($userManager->checkOldPassword($user, $formData['plainPasswordOld'])) {
                if ($userManager->checkPasswordConfirmation($user,
                        $formData['plainPassword'],
                        $formData['plainPasswordConfirmation']) && $userManager->update($user)) {
                    $this->addFlash('success', 'Votre mot de passe à bien été modifié !');

                    return $this->redirectToRoute('profil_home');
                } else {
                    $this->addFlash('danger', 'Erreur lors de la modification : ' .
                        $userManager->getErrors($user));
                }
            } else {
                $this->addFlash('danger', 'L\'ancien mot de passe est incorrect.');
            }
        }

        return $this->render('profil/passwordChange.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
