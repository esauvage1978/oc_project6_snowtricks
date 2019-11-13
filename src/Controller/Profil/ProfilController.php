<?php

namespace App\Controller\Profil;

use App\Form\Profil\ProfilType;
use App\Service\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/profil")
 * @IsGranted("ROLE_USER")
 */
class ProfilController extends AbstractController
{

    /**
     * @Route("/", name="profil_home")
     *
     * @var Request $request
     * @var UserManager $userManager
     *
     * @return Response
     *
     */
    public function profilHomeAction(Request $request,  UserManager $manager): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfilType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($manager->update($user)) {
                $this->addFlash('success', 'Modification effectué');
            } else {
                $this->addFlash('danger', 'Erreur lors de la modification : ' . $manager->getErrors($user));
            }
        }

        return $this->render('profil/home.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }


}
