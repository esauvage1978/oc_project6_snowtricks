<?php

namespace App\Validator;

use App\Entity\Trick;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TrickValidator
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
     * @param Trick $trick
     * @return bool
     */
    public function isValid(Trick $trick): bool
    {
        $this->errors = $this->validator->validate($trick);
        return  !count($this->errors)?true:false;
    }

    /**
     * @param Trick $trick
     * @return string|null
     */
    public function getErrors(Trick $trick): ?string
    {
        return (string)$this->errors;
    }
}
