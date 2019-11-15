<?php

namespace App\Helper;

use App\Entity\User;
use Swift_Mailer;
use Swift_Message;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Twig\Environment;

class UserSendmail
{
    const LOGIN = 'login';
    const VALIDATE = 'validate';
    const REGISTRATION = 'register';

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var Swift_Mailer
     */
    private $mailer;

    /**
     * Summary of $params.
     *
     * @var ParameterBagInterface
     */
    private $params;

    public function __construct(Environment $twig, Swift_Mailer $mailer, ParameterBagInterface $params)
    {
        $this->twig = $twig;
        $this->mailer = $mailer;
        $this->params = $params;
    }

    public function send(User $user, string $context, string $objet = null): int
    {
        $message = (new Swift_Message())
         ->setSubject($objet ? $objet : $context)
         ->setFrom([
         $this->params->get('mailer.mail') => $this->params->get('mailer.name'), ])
         ->setTo([$user->getEmail() => $user->getUsername()])
         ->setBody(
             $this->twig->render('email/'.$context.'.html.twig', ['user' => $user]),
             'text/html'
         );

        return $this->mailer->send($message, $failures);
    }
}
