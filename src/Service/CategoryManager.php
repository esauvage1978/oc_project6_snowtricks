<?php

namespace App\Service;

use App\Entity\Category;
use App\Validator\CategoryValidator;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryManager
{
    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @var CategoryValidator
     */
    private $validator;

    /**
     * CategoryManager constructor.
     * @param ObjectManager $manager
     * @param CategoryValidator $validator
     */
    public function __construct(ObjectManager $manager, CategoryValidator $validator)
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
        if (!$this->validator->isValide($category)) {
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
