<?php

namespace App\Validator;

use App\Entity\User;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserValidator
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * UserValidator constructor.
     * @param ValidatorInterface $validator
     */
    public function __construct( ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function isValide(User $user): bool
    {
        return  $this->getErrors($user)===null;
    }

    /**
     * @param User $user
     * @return string|null
     */
    public function getErrors(User $user): ?string
    {
        $error = $this->validator->validate($user);
        return !count($error)?null:(string)$error;
    }
}
