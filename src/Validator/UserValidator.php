<?php

namespace App\Validator;

use App\Entity\User;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserValidator
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var ConstraintViolationListInterface
     */
    private $errors;

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
        $this->errors = $this->validator->validate($user);
        return  !count($this->errors)?true:false;
    }

    /**
     * @param User $user
     * @return string|null
     */
    public function getErrors(User $user): ?string
    {
        return (string)$this->errors;
    }
}
