<?php

namespace App\Controller\Profil;

use App\Form\Profil\PasswordChangeFormType;
use App\Entity\User;
use App\Event\UserRegistrationEvent;
use App\Form\Profil\ProfilType;
use App\Form\Profil\RegistrationType;
use App\Helper\UserSendmail;
use App\Repository\UserRepository;
use App\Service\UserManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profil")
 */
class ProfilController extends AbstractController
{
    /**
     * @route("/email/validated/{token}", name="profil_email_validated")
     *
     * @param string $token
     * @param UserRepository $userRepository
     * @param UserManager $userManager
     * @return Response
     */
    public function profilEmailValidatedAction(string $token,UserRepository $userRepository, UserManager $userManager): Response
   {
       $user = $userRepository->findOneBy(['emailValidatedToken'=>$token]);

       if (null===$user) {
           $this->addFlash('warning', 'L\'adresse d\'activation est incorrecte!');
       } else {
           $userManager->active($user);

           if ($userManager->update($user)) {
               $this->addFlash('success', 'Votre compte est activé. Vous pouvez vous connecter!');
           } else {
               $this->addFlash('danger', 'Echec de la mise à jour' . $userManager->getErrors($user));
           }
       }

       return $this->redirectToRoute('home');
   }

    /**
     * @Route("/", name="profil_home", methods={"GET","POST"})
     *
     * @param UserManager $userManager
     * @param Request $request
     * @return Response
     * @IsGranted("ROLE_USER")
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
  
  /**
     * @Route("/avatar", name="profil_avatar")
     *
     * @return Response
     *
     * @IsGranted("ROLE_USER")
     */
    public function avatarAction(): Response
    {
        return $this->render('profil/avatar.html.twig');
    }



    /**
     * @Route("/avatar/update", name="profil_avatar_update")
     *
     * @IsGranted("ROLE_USER")
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
     *
     * @IsGranted("ROLE_USER")
     */
    public function sendmailActivationAction(UserSendMail $mail): Response
    {
        $user=$this->getUser();
        $mail->send($user,UserSendmail::VALIDATE);
        $this->addFlash('success', 'Le mail est envoyé, merci de consulter votre messagerie.');
        return $this->redirectToRoute('profil_home');

    }
}
