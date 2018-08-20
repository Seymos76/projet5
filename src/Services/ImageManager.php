<?php
/**
 * Created by PhpStorm.
 * User: seymos
 * Date: 09/08/18
 * Time: 17:48
 */

namespace App\Services;


use App\Entity\Capture;
use App\Entity\Image;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Security\User\EntityUserProvider;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\SessionStorageInterface;

class ImageManager
{
    private $manager;

    private $user;

    private $container;

    public function __construct(NAOManager $manager, EntityUserProvider $user, ContainerInterface $container)
    {
        $this->manager = $manager;
        $this->user = $user;
        $this->container = $container;
    }

    /**
     * @param string $username
     * @return bool
     * @throws \Exception
     */
    public function removeCurrentAvatar(string $username): bool
    {
        // get current avatar form database
        $user = $this->getUser()->loadUserByUsername($username);
        $current_image = $this->container->get('doctrine')->getRepository(Image::class)->findOneBy(
            array(
                'id' => $user->getAvatar()
            )
        );
        if ($current_image instanceof Image) {
            // get avatar directory
            $dir = $this->getContainer()->getParameter('avatar_directory');
            // get current file name
            $current_image_filename = $current_image->getFilename();
            // get current avatar from directory
            $current_avatar = $this->getContainer()->getParameter('avatar_directory').'/'.$current_image_filename;
            self::deleteFile($current_avatar);
            // set user avatar to null
            $user->setAvatar(null);
            $this->getManager()->addOrModifyEntity($current_image);
            $this->getManager()->addOrModifyEntity($user);
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param Capture $capture
     * @param Image $image
     * @param NAOManager $manager
     * @return bool
     */
    public function removeCaptureImage(Capture $capture, Image $image, NAOManager $manager): bool
    {
        $capture->removeImage();
        $current_image = $this->getContainer()->getParameter('bird_directory').'/'.$image->getFileName();
        self::deleteFile($current_image);
        $manager->removeEntity($image);
        $manager->addOrModifyEntity($capture);
        return true;
    }

    /**
     * @param $file
     * @return bool
     */
    public function deleteFile($file)
    {
        if (file_exists($file)) {
            unlink($file);
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param UploadedFile $uploadedFile
     * @param string $directory
     * @return Image
     * @throws \Exception
     */
    public function buildImage(UploadedFile $uploadedFile, string $directory): Image
    {
        $image = new Image();
        $image->setPath($directory);
        $image->setMimeType($uploadedFile->getMimeType());
        $image->setExtension($uploadedFile->guessExtension());
        $image->setSize($uploadedFile->getSize());
        // upload file to directory
        $file_name = $this->getContainer()->get('app.nao.file_uploader')->upload($uploadedFile, $directory);
        $image->setFileName($file_name);
        $this->getManager()->addOrModifyEntity($image);
        return $image;
    }

    /**
     * @return NAOManager
     */
    public function getManager(): NAOManager
    {
        return $this->manager;
    }

    /**
     * @return EntityUserProvider
     */
    public function getUser(): EntityUserProvider
    {
        return $this->user;
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }
}