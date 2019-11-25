<?php

namespace App\Service;

use App\Entity\Trick;
use App\Entity\User;
use App\Helper\Slugger;
use App\Validator\TrickValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class TrickManager
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var TrickValidator
     */
    private $validator;

    /**
     * @var Security
     */
    private $securityContext;

    public function __construct(EntityManagerInterface $manager, TrickValidator $validator, Security $securityContext)
    {
        $this->manager = $manager;
        $this->validator = $validator;
        $this->securityContext = $securityContext;
    }

    public function update(Trick $trick): bool
    {
        $this->initialise($trick);

        if (!$this->validator->isValid($trick)) {
            return false;
        }

        $this->manager->persist($trick);
        $this->manager->flush();

        return true;
    }

    public function initialise(Trick $trick)
    {
        if($trick->getCreateAt() ===null) {
            $trick->setCreateAt(new \DateTime());
        } else {
            $trick->setModifyAt(new \DateTime());
        }

        foreach ($trick->getVideos() as $video)
        {
            $video->setTrick($trick);
        }

        foreach ($trick->getImages() as $image)
        {
            $image->setTrick($trick);
        }

        if(empty( $trick->getUser())) {
            $trick->setUser( $this->securityContext->getToken()->getUser());
        }

        $trick->setSlug(
            Slugger::slugify($trick->getName())
        );
    }

    public function getError(Trick $trick)
    {
        return $this->validator->getErrors($trick);
    }

    /**
     * @param Trick $trick
     */
    public function remove(Trick $trick)
    {
        $this->manager->remove($trick);
        $this->manager->flush();
    }
}
