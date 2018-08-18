<?php
/**
 * Created by PhpStorm.
 * User: seymos
 * Date: 14/08/18
 * Time: 16:21
 */

namespace App\DataFixtures;


use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 5; $i++) {
            $user = new User();
            $user->setUsername("user$i");
            $user->setAccountType('particular');
            $user->setActive(0);
            $user->setBiography("Ma biographie");
            $user->setPassword($this->encoder->encodePassword($user, "user$i"));
            $user->setEmail("user$i@nao.fr");
            $user->setLastname("lastname$i");
            $user->setFirstname("firstname$i");
            $manager->persist($user);
            $this->addReference("user".$i, $user);
        }
        $manager->flush();
    }
}