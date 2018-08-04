<?php
/**
 * Created by PhpStorm.
 * User: seymos
 * Date: 03/08/18
 * Time: 21:25
 */

namespace App\Services;


use App\Entity\User;
use Psr\Container\ContainerInterface;

class Mailer
{
    private $mailer;

    private $container;

    public function __construct(\Swift_Mailer $mailer, ContainerInterface $container)
    {
        $this->mailer = $mailer;
        $this->container = $container;
    }

    public function sendConfirmationEmail(User $user, string $token)
    {
        $message = (new \Swift_Message("Register confirmation"))
            ->setFrom("sent@nao.fr")
            ->setTo($user->getEmail())
            ->setBody(
                $this->container->get('twig')->render('email/register_confirmation.html.twig', ['user' => $user, 'token' => $token])
            );
        $this->mailer->send($message);
        return true;
    }
}