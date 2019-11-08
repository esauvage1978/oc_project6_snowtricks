<?php

namespace App\Service;

use App\Entity\User;
use App\Validator\UserValidator;
use Doctrine\Common\Persistence\ObjectManager;

class UserManager
{
    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @var UserValidator
     */
    private $validator;

    public function __construct(ObjectManager $manager, UserValidator $validator)
    {
        $this->manager = $manager;
        $this->validator = $validator;
    }

    public function update(User $user): bool
    {
        if (!$this->validator->isValide($user)) {
            return false;
        }

        $this->manager->persist($user);
        $this->manager->flush();

        return true;
    }


    public function getError(User $entity)
    {
        return $this->validator->getErrors($entity);
    }

}
