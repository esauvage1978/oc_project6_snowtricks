<?php

namespace App\Validator;

use App\Entity\Category;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CategoryValidator
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
     * @param Category $category
     * @return bool
     */
    public function isValide(Category $category): bool
    {
        return $this->getErrors($category) === null;
    }

    /**
     * @param Category $trick
     * @return string|null
     */
    public function getErrors(Category $category): ?string
    {
        $error = $this->validator->validate($category);
        return !count($error) ? null : (string)$error;
    }
}
