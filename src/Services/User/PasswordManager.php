<?php
/**
 * Created by PhpStorm.
 * User: seymos
 * Date: 17/08/18
 * Time: 23:39
 */

namespace App\Services\User;


use App\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;
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
    public function changePassword(User $user, string $password)
    {
        /** @var User $user */
        $user = $this->container->get('doctrine.orm.default_entity_manager')->getRepository(User::class)->findUserByEmail($user->getEmail());
        $encoded = $this->encoder->encodePassword($user, $password);
        $user->setPassword($encoded);
        $this->container->get('app.nao_manager')->addOrModifyEntity($user);
        $this->container->get('app.nao.mailer')->sendConfirmationPasswordChanged($user);
        $this->container->get('session')->getFlashBag()->add('success', "Votre mot de passe a été changé avec succès !");
        return true;
    }
}