<?php


namespace App\Controller\User;

use App\Entity\User;
use App\Event\UserPasswordForgetEvent;
use App\Event\UserRegistrationEvent;
use App\Form\User\PasswordForgetFormType;
use App\Form\User\PasswordRecoverFormType;
use App\Form\User\RegistrerType;
use App\Form\User\UserEditType;
use App\Form\User\UserType;
use App\Repository\UserRepository;
use App\Service\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     * @param Request $request
     * @param UserManager $manager
     *
     * @return Response
     * @isGranted("ROLE_ADMIN")
     */
    public function newAction(Request $request, UserManager $manager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($manager->update($user)) {
                $this->addFlash('success', 'Création de l\'utilisateur effectuée');

                return $this->redirectToRoute('home');
            }
            $this->addFlash('danger', 'La création a echoué. En voici les raisons : ' . $manager->getErrors($user));
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/registrer", name="user_registrer", methods={"GET","POST"})
     *
     * @param Request $request
     * @param UserManager $manager
     * @param EventDispatcherInterface $dispatcher
     *
     * @return Response
     */
    public function registrationAction(Request $request, UserManager $manager, EventDispatcherInterface $dispatcher): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrerType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($manager->update($user)) {
                $this->addFlash('success', 'Création de l\'utilisateur effectuée. Un mail de validation du compte vous a été envoyé');

                $event = new UserRegistrationEvent($user);
                $dispatcher->dispatch($event, UserRegistrationEvent::NAME);

                return $this->redirectToRoute('home');
            }
            $this->addFlash('danger', 'La création a echoué. En voici les raisons : ' . $manager->getErrors($user));
        }

        return $this->render('user/registrer.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     *
     * @param Request $request
     * @param User $user
     * @param UserManager $userManager
     * @return Response
     * @isGranted("ROLE_ADMIN")
     */
    public function deleteAction(Request $request, User $user, UserManager $userManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {

            $this->addFlash('success', 'L\{utilisateur est supprimé');
            $userManager->remove($user);
        }

        return $this->redirectToRoute('user_index');
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     *
     * @param User $user
     * @return Response
     */
    public function showAction(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @route("/password/recover/{token}", name="user_password_recover", methods={"GET","POST"})
     *
     * @param Request $request
     * @param string $token
     * @param UserRepository $userRepository
     * @param UserManager $userManager
     *
     * @return Response
     */
    public function userPasswordRecorverction(
        Request $request,
        string $token,
        UserRepository $userRepository,
        UserManager $userManager
    ): Response
    {
        $form = $this->createForm(PasswordRecoverFormType::class);
        $form->handleRequest($request);
        $user = $userRepository->findOneBy(['passwordForgetToken' => $token]);

        if (!$user) {
            $this->addFlash('warning', 'L\'adresse de récupération du mot de passe est incorrecte !');
            return $this->redirectToRoute('home');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            if ($userManager->initialisePasswordRecover($user,
                    $formData['plainPassword'],
                    $formData['plainPasswordConfirmation']) &&

                $userManager->update($user)) {
                $this->addFlash('success', 'Votre mot de passe est changé. Vous pouvez vous connecter !');

                return $this->redirectToRoute('home');
            }
            $this->addFlash('danger', 'La modification a echoué. En voici les raisons : ' . $userManager->getErrors($user));

        }

        return $this->render('user/passwordRecover.html.twig', [
            'form' => $form->createView(),]);
    }

    /**
     * @Route("/password/forget", name="user_password_forget", methods={"GET","POST"})
     *
     * @param Request $request
     * @param UserRepository $userRepository
     * @param UserManager $userManager
     * @param EventDispatcherInterface $dispatcher
     *
     * @return Response
     */
    public function passwordForgetAction(Request $request, UserRepository $userRepository, UserManager $userManager, EventDispatcherInterface $dispatcher): Response
    {
        $form = $this->createForm(PasswordForgetFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $user = $userRepository->findOneBy(['email' => $formData['email']]);

            if (null !== $user) {
                $this->addFlash('success', 'Le mail de récupération du mot de passe est envoyé !');

                $userManager->initialisePasswordForget($user);
                $userManager->update($user);

                $event = new UserPasswordForgetEvent($user);
                $dispatcher->dispatch($event, UserPasswordForgetEvent::NAME);

                return $this->redirectToRoute('home');
            }
            $this->addFlash('danger', 'L\'utilisateur n\'a pas été trouvé.');
        }

        return $this->render('user/password_forget.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/", name="user_index", methods={"GET"})
     *
     * @param UserRepository $userRepository
     * @return Response
     */
    public function indexAction(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }


    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     * @param Request $request
     * @param UserManager $manager
     * @param User $user
     *
     * @return Response
     * @isGranted("ROLE_ADMIN")
     */
    public function editAction(Request $request, User $user, UserManager $manager): Response
    {
        $oldEmail= $user->getEmail();
        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($manager->update($user,$oldEmail)) {
                $this->addFlash('success', 'Modification de l\'utilisateur effectuée');

                return $this->redirectToRoute('user_index');
            }
            $this->addFlash('danger', 'La modification a echoué. En voici les raisons : ' . $manager->getErrors($user));
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
