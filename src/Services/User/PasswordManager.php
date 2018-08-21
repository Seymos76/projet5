<?php
/**
 * Created by PhpStorm.
 * User: seymos
 * Date: 17/08/18
 * Time: 23:39
 */

namespace App\Services\User;


use App\Entity\User;
use App\Services\Mailer;
use App\Services\NAOManager;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordManager
{
    private $encoder;

    private $container;

    public function __construct(UserPasswordEncoderInterface $encoder, ContainerInterface $container)
    {
        $this->encoder = $encoder;
        $this->container = $container;
    }

    /**
     * @param User $user
     * @param string $password
     * @return mixed
     */
    public function changePassword(User $user, string $password, EntityManager $entityManager, NAOManager $manager, Mailer $mailer, Session $session)
    {
        /** @var User $user */
        $user = $entityManager->getRepository(User::class)->findUserByEmail($user->getEmail());
        $encoded = $this->encoder->encodePassword($user, $password);
        $user->setPassword($encoded);
        $manager->addOrModifyEntity($user);
        $mailer->sendConfirmationPasswordChanged($user);
        $session->getFlashBag()->add('success', "Votre mot de passe a été changé avec succès !");
        return true;
    }
}