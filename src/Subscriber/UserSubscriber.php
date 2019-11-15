<?php

namespace App\Subscriber;

use App\Event\UserRegistrationEvent;
use App\Helper\UserSendmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserSubscriber implements EventSubscriberInterface
{
    /**
     * @var UserSendMail
     */
    private $sendmail;

    public function __construct(UserSendMail $sendmail)
    {
        $this->sendmail = $sendmail;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            UserRegistrationEvent::NAME => 'onUserRegistration'
        ];
    }

    /**
     * @param UserRegistrationEvent $event
     * @return int
     */
    public function onUserRegistration(UserRegistrationEvent $event): int
    {
        return $this->sendmail->send($event->getUser(), $this->sendmail::REGISTRATION);
    }

}
