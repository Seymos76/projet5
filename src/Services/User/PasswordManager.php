<?php
/**
 * Created by PhpStorm.
 * User: seymos
 * Date: 17/08/18
 * Time: 23:39
 */

namespace App\Services\User;


use App\Entity\User;
use App\Services\Mail\Mailer;
use App\Services\NAOManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordManager
{
    private $encoder;

    private $mailer;

    private $session;

    public function __construct(UserPasswordEncoderInterface $encoder, Mailer $mailer, SessionInterface $session)
    {
        $this->encoder = $encoder;
        $this->mailer = $mailer;
        $this->session = $session;
    }

    /**
     * @param User $user
     * @param string $password
     * @param NAOManager $manager
     * @return bool
     */
    public function changePassword(User $user, string $password, NAOManager $manager)
    {
        /** @var User $user */
        $user = $manager->getEm()->getRepository(User::class)->findUserByEmail($user->getEmail());
        $encoded = $this->encoder->encodePassword($user, $password);
        $user->setPassword($encoded);
        $manager->addOrModifyEntity($user);
        $this->mailer->sendConfirmationPasswordChanged($user);
        $this->session->getFlashBag()->add('success', "Votre mot de passe a été changé avec succès !");
        return true;
    }
}
