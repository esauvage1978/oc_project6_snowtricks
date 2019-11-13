<?php

namespace App\Service;

use App\Entity\Trick;
use App\Helper\Slugger;
use App\Validator\TrickValidator;
use Doctrine\Common\Persistence\ObjectManager;

class TrickManager
{
    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @var TrickValidator
     */
    private $validator;

    public function __construct(ObjectManager $manager, TrickValidator $validator)
    {
        $this->manager = $manager;
        $this->validator = $validator;
    }

    public function update(Trick $trick): bool
    {
        $this->initialise($trick);

        if (!$this->validator->isValide($trick)) {
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

        $trick->setSlug(
            Slugger::slugify($trick->getName())
        );
    }

    public function getError(Trick $trick)
    {
        return $this->validator->getErrors($trick);
    }

}
