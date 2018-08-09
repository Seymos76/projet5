<?php
/**
 * Created by PhpStorm.
 * User: seymos
 * Date: 09/08/18
 * Time: 17:48
 */

namespace App\Services;


use App\Entity\Image;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Security\User\EntityUserProvider;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\SessionStorageInterface;

class Avatar
{

    private $manager;

    private $session;

    private $user;

    private $container;

    private $NAOManager;

    public function __construct(EntityManagerInterface $manager, Session $session, EntityUserProvider $user, NAOManager $NAOManager, Container $container)
    {
        $this->manager = $manager;
        $this->session = $session;
        $this->user = $user;
        $this->NAOManager = $NAOManager;
        $this->container = $container;
    }

    public function removeCurrentAvatar(string $username): bool
    {
        // get current avatar form database
        $user = $this->getUser()->loadUserByUsername($username);
        $current_image = $this->getManager()->getRepository(Image::class)->findOneBy(
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
            $this->getNAOManager()->removeEntity($current_image);
            // flush
            $this->getNAOManager()->addOrModifyEntity($this->getUser());
            return true;
        } else {
            return false;
        }

    }

    public function deleteFile($file)
    {
        if (file_exists($file)) {
            unlink($file);
        }
    }

    public function buildAvatar(UploadedFile $uploadedFile): Image
    {
        $image = new Image();
        $image->setPath($this->getContainer()->getParameter('avatar_directory'));
        $image->setMimeType($uploadedFile->getMimeType());
        $image->setExtension($uploadedFile->guessExtension());
        $image->setSize($uploadedFile->getSize());
        // upload file to directory
        $file_name = $this->getContainer()->get('app.nao.file_uploader')->upload($uploadedFile, $this->getContainer()->getParameter('avatar_directory'));
        $image->setFileName($file_name);
        $this->getNAOManager()->addOrModifyEntity($image);
        return $image;
    }

    /**
     * @return EntityUserProvider
     */
    public function getUser(): EntityUserProvider
    {
        return $this->user;
    }

    /**
     * @return Session
     */
    public function getSession(): Session
    {
        return $this->session;
    }

    /**
     * @return EntityManagerInterface
     */
    public function getManager(): EntityManagerInterface
    {
        return $this->manager;
    }

    /**
     * @return NAOManager
     */
    public function getNAOManager(): NAOManager
    {
        return $this->NAOManager;
    }

    /**
     * @return Container
     */
    public function getContainer(): Container
    {
        return $this->container;
    }
}