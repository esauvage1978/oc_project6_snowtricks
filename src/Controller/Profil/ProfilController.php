<?php

namespace App\Controller\Profil;

use App\Form\Profil\ProfilType;
use App\Helper\UserSendmail;
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

    /**
     * @Route("/avatar", name="profil_avatar")
     *
     * @return Response
     */
    public function showAction(): Response
    {
        return $this->render('profil/avatar.html.twig');
    }

    /**
     * @Route("/avatar/update", name="profil_avatar_update")
     */
    public function ajaxAction(Request $request, UserManager $userManager)
    {
        $user = $this->getUser();

        /* on récupère la valeur envoyée par la vue */
        $image = $request->request->get('dataImg');

        $userManager->avatarAdd($user, $image);
        $userManager->update($user);

        $response = new Response(json_encode([
            'retour' => 'Avatar mis à jour',
        ]));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
  
    /**
     * @Route("/profil/sendmail/emailvalidated", methods={"GET"}, name="profil_sendmail_email_validated")
     * @param UserSendmail $mail
     * @return Response
     */
    public function sendmailActivationAction(UserSendMail $mail): Response
    {
        $user=$this->getUser();
        $mail->send($user,UserSendmail::VALIDATE);
        $this->addFlash('success', 'Le mail est envoyé, merci de consulter votre messagerie.');
        return $this->redirectToRoute('profil_home');

    }
}
