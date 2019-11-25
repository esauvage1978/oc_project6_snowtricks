<?php

namespace App\Service;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Entity\User;
use App\Validator\CommentValidator;
use Doctrine\ORM\EntityManagerInterface;

class CommentManager
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var CommentValidator
     */
    private $validator;

    public function __construct(EntityManagerInterface $manager,
                                CommentValidator $validator)
    {
        $this->manager = $manager;
        $this->validator = $validator;
    }

    public function update(Comment $comment, Trick $trick, User $user): bool
    {
        $this->initialise($comment,$trick, $user);

        if (!$this->validator->isValid($comment)) {
            return false;
        }

        $this->manager->persist($comment);
        $this->manager->flush();

        return true;
    }

    public function initialise(Comment $comment, Trick $trick, User $user)
    {
        if($comment->getCreatedAt() ===null) {
            $comment->setCreatedAt(new \DateTime());
        }

        $comment->setUser($user);

        $comment->setTrick($trick);

    }


    public function getErrors(Comment $comment)
    {
        return $this->validator->getErrors($comment);
    }

}
