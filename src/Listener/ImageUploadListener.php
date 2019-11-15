<?php

namespace App\Listener;

use App\Entity\Image;
use App\Service\Uploader;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ImageUploadListener
{
    /**
     * @var Uploader
     */
    private $uploader;

    /**
     * @var string
     */
    private $directory;


    public function __construct(Uploader $uploader,string $directory)
    {
        $this->uploader = $uploader;
        $this->directory = $directory;
    }

    /**
     * @param Image $image
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function prePersistHandler(Image $image)
    {

        $extension = $this->uploader->getExtension($image->getFile());

        $image->setName(md5(uniqid()));
        $image->setExtension($extension);
    }

    /**
     * @param Image $image
     *
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function postPersistHandler(Image $image)
    {
        if ($image->getFilename() !== null) {
            $oldFile = $this->directory.'/'.$image->getFilename();
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }

        $this->uploader->setTargetDir($this->directory);
        $this->uploader->upload($image->getFile(), $image->getName());

    }

    /**
     * @param Image $image
     *
     * @ORM\PostRemove()
     */
    public function postRemoveHandler(Image $image)
    {
        $file = $this->directory.'/'.$image->getFilename();
        if (file_exists($file)) {
            unlink($file);
        }
    }
}
