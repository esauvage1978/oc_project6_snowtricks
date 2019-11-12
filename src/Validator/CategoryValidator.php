<?php

namespace App\Validator;

use App\Entity\Category;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CategoryValidator
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
     * CategoryValidator constructor.
     * @param ValidatorInterface $validator
     */
    public function __construct( ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param Category $category
     * @return bool
     */
    public function isValid(Category $category): bool
    {
        $this->errors = $this->validator->validate($category);
        return  !count($this->errors)?true:false;
    }

    /**
     * @param Category $category
     * @return string|null
     */
    public function getErrors(Category $category): ?string
    {
        return (string)$this->errors;
    }
}
