<?php

// src/Security/PostVoter.php
namespace App\Security;

use App\Entity\Trick;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class TrickVoter extends Voter
{
    const UPDATE = 'update';
    const DELETE = 'DELETE';
    const CREATE = 'create';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
// if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::CREATE, self::UPDATE, self::DELETE])) {
            return false;
        }

// only vote on Post objects inside this voter
        if (!$subject instanceof Trick) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        /** @var Trick $trick */
        $trick = $subject;

        switch ($attribute) {
            case self::UPDATE:
                return $this->canUpdate($trick, $user);
            case self::CREATE:
                return $this->canCreate($trick, $user);
            case self::DELETE:
                return $this->canDelete($trick, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }


    private function canUpdate(Trick $trick, User $user)
    {
        if ($this->security->isGranted('ROLE_GESTIONNAIRE')) {
            return true;
        }
        return $user === $trick->getUser();
    }

    private function canCreate(Trick $trick, User $user)
    {
        if ($this->security->isGranted('ROLE_USER')) {
            return true;
        }
        return false;
    }

    private function canDelete(Trick $trick, User $user)
    {
        if ($this->security->isGranted('ROLE_GESTIONNAIRE')) {
            return true;
        }
        return $user === $trick->getUser();
    }
}