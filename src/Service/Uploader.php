<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class Uploader
{
    /**
     * @var string
     */
    private $targetDir;


    public function __construct()
    {
    }

    /**
     * @param UploadedFile $file
     * @param string $name
     */
    public function upload(UploadedFile $file, string $name)
    {
        $extension = $this->getExtension($file);
        $file->move(
            $this->getTargetDir(),
            $name.'.'.$extension
        );
    }

    /**
     * @param string $directory
     */
    public function setTargetDir($directory)
    {
        $this->targetDir = $directory;
    }

    /**
     * @return string
     */
    public function getTargetDir(): string
    {
        return $this->targetDir;
    }

    /**
     * @param UploadedFile $file
     *
     * @return string
     */
    public function getExtension(UploadedFile $file): string
    {
        return $file->guessExtension();
    }

    /**
     * @param UploadedFile $file
     *
     * @return string
     */
    public function getOriginalName(UploadedFile $file): string
    {
        return $file->getClientOriginalName();
    }
}
