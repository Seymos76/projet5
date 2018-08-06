<?php
/**
 * Created by PhpStorm.
 * User: seymos
 * Date: 02/08/18
 * Time: 21:05
 */

namespace App\Services;


use App\Entity\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    /*private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }*/

    public function upload(UploadedFile $file, $targetDirectory): string
    {
        /*switch ($targetDirectory) {
            case 'avatar_directory':
                if ($this->getUser()->getAvatar() !== null) {
                    unlink($this->getUser()->getAvatar(), $targetDirectory);
                    $this->getUser()->setAvatar(null);
                }
                break;
            case 'bird_directory':
                // si bird->getPicture() !== null
                // unset(link);
                // bird->setPicture(null);
                break;
        }*/
        $file_name = md5(uniqid()).'/'.$file->guessExtension();
        $file->move(
            $targetDirectory,
            $file_name
        );
        return $file_name;
    }

    /**
     * @return User
     */
    /*public function getUser(): User
    {
        return $this->user;
    }*/
}
