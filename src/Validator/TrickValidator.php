<?php

namespace App\Validator;

use App\Entity\Trick;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TrickValidator
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * UserValidator constructor.
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param Trick $trick
     * @return bool
     */
    public function isValide(Trick $trick): bool
    {
        return $this->getErrors($trick) === null;
    }

    /**
     * @param Trick $trick
     * @return string|null
     */
    public function getErrors(Trick $trick): ?string
    {
        $error = $this->validator->validate($trick);
        return !count($error) ? null : (string)$error;
    }
}
