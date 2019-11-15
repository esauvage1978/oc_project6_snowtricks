<?php

namespace App\Event;

use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;


class UserRegistrationEvent  extends Event
{
    const NAME = 'user.registration';

    /**
     * @var User
     */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}
