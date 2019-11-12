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
    public function isValid(User $user): bool
    {
        $error = $this->validator->validate($user);
        return  !count($error)?true:false;
    }

    /**
     * @param User $user
     * @return string|null
     */
    public function getErrors(User $user): ?string
    {
        return (string)$this->validator->validate($user);
    }
}
