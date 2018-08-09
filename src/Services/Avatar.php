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
            if (file_exists($current_avatar)) {
                unlink($current_avatar);
            }
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