<?php

namespace App\Listener;

use App\Helper\UserSendmail;
use App\Service\UserManager;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;


class UserLoginListener
{
    /**
     * @var UserSendMail
     */
    private $sendmail;

    /**
     * @var UserManager
     */
    private $userManager;

    public function __construct(UserSendMail $sendmail, UserManager $userManager)
    {
        $this->sendmail = $sendmail;
        $this->userManager = $userManager;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event): ?int
    {
        $user = $event->getAuthenticationToken()->getUser();

        if (!$user->getEmailValidated()) {
            return $this->sendmail->send($user, $this->sendmail::LOGIN);
        }

        return null;
    }
}
