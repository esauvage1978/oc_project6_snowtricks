<?php

namespace App\Validator;

use App\Entity\Comment;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CommentValidator
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
     * @param Comment $comment
     * @return bool
     */
    public function isValid(Comment $comment): bool
    {
        $this->errors = $this->validator->validate($comment);
        return  !count($this->errors)?true:false;
    }

    /**
     * @param Comment $comment
     * @return string|null
     */
    public function getErrors(Comment $comment): ?string
    {
        return (string)$this->errors;
    }
}
