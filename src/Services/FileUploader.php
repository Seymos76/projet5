<?php
/**
 * Created by PhpStorm.
 * User: seymos
 * Date: 02/08/18
 * Time: 21:05
 */

namespace App\Services;


use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDirectory;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function upload(UploadedFile $file, string $username)
    {
        if (!$file) {
            return false;
        }
        $fileName = $username.'.'.$file->guessExtension();

        // moves the file to the directory where brochures are stored
        $file->move($this->getTargetDirectory(), $fileName);
        return $fileName;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}