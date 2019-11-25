<?php

namespace App\Service;

use App\Entity\Category;
use App\Validator\CategoryValidator;
use Doctrine\ORM\EntityManagerInterface;

class CategoryManager
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var CategoryValidator
     */
    private $validator;

    /**
     * CategoryManager constructor.
     * @param EntityManagerInterface $manager
     * @param CategoryValidator $validator
     */
    public function __construct(EntityManagerInterface $manager, CategoryValidator $validator)
    {
        $this->manager = $manager;
        $this->validator = $validator;
    }

    /**
     * @param Category $category
     * @return bool
     */
    public function update(Category $category): bool
    {
        if (!$this->validator->isValid($category)) {
            return false;
        }

        $this->manager->persist($category);
        $this->manager->flush();

        return true;
    }

    /**
     * @param Category $category
     * @return string|null
     */
    public function getError(Category $category)
    {
        return $this->validator->getErrors($category);
    }

    /**
     * @param Category $category
     */
    public function remove(Category $category)
    {
        $this->manager->remove($category);
        $this->manager->flush();
    }

}
