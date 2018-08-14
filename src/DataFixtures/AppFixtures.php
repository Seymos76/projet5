<?php

// src/DataFixtures/AppFixtures.php
namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Capture;
use App\Entity\Comment;
use App\Entity\Bird;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;

class AppFixtures extends Fixture
{
    private $encoder,
            $em;

    public function __construct(UserPasswordEncoderInterface $encoder, EntityManagerInterface $em)
    {
        $this->encoder = $encoder;
        $this->em = $em;
    }

    public function load(ObjectManager $manager)
    {

        $user1 = new user();
        $user1->setUsername('username1');
        $password1 = $this->encoder->encodePassword($user1, 'pass_1234');
        $user1->setPassword($password1);
        $user1->setEmail('user1@test.fr');
        $user1->setLastname('lastname1');
        $user1->setFirstname('firstname1');
        $role1 = [];
        $role1[] = 'particular';
        $user1->setRoles($role1);

        $user2 = new user();
        $user2->setUsername('username2');
        $password2 = $this->encoder->encodePassword($user2, 'pass_5678');
        $user2->setPassword($password2);
        $user2->setEmail('user2@test.fr');
        $user2->setLastname('lastname2');
        $user2->setFirstname('firstname2');
        $role2 = [];
        $role2[] = 'particular';
        $user2->setRoles($role2);

        $user3 = new user();
        $user3->setUsername('username3');
        $password3 = $this->encoder->encodePassword($user3, 'pass_9999');
        $user3->setPassword($password3);
        $user3->setEmail('user3@test.fr');
        $user3->setLastname('lastname3');
        $user3->setFirstname('firstname3');
        $role3 = [];
        $role3[] = 'naturalist';
        $user3->setRoles($role3);

        $user4 = new user();
        $user4->setUsername('username4');
        $password4 = $this->encoder->encodePassword($user4, 'pass_1111');
        $user4->setPassword($password4);
        $user4->setEmail('user4@test.fr');
        $user4->setLastname('lastname4');
        $user4->setFirstname('firstname4');
        $role4 = [];
        $role4[] = 'administrator';
        $user4->setRoles($role4);

        $bird = $this->em->getRepository(Bird::Class)->findOneById('1');

        $capture1 = new Capture();
        $capture1->setContent('Capture numéro 1');
        $capture1->setLatitude('48.70981');
        $capture1->setLongitude('2.041563');
        $capture1->setAddress('Chemin Jean Racine');
        $capture1->setZipcode('78460');
        $capture1->setCity('Chevreuse');
        $capture1->setStatus('published');
        $capture1->setUser($user3);
        $capture1->setBird($bird);

        $capture2 = new Capture();
        $capture2->setContent('Capture numéro 2');
        $capture2->setLatitude('48.70981');
        $capture2->setLongitude('2.041563');
        $capture2->setAddress('Chemin Jean Racine');
        $capture2->setZipcode('78460');
        $capture2->setCity('Chevreuse');
        $capture2->setStatus('draft');
        $capture2->setUser($user3);
        $capture2->setBird($bird);

        $capture3 = new Capture();
        $capture3->setContent('Capture numéro 3');
        $capture3->setLatitude('48.70981');
        $capture3->setLongitude('2.041563');
        $capture3->setAddress('Chemin Jean Racine');
        $capture3->setZipcode('78460');
        $capture3->setCity('Chevreuse');
        $capture3->setStatus('waiting for validation');
        $capture3->setUser($user1);
        $capture3->setBird($bird);

        $capture4 = new Capture();
        $capture4->setContent('Capture numéro 4');
        $capture4->setLatitude('48.70981');
        $capture4->setLongitude('2.041563');
        $capture4->setAddress('Chemin Jean Racine');
        $capture4->setZipcode('78460');
        $capture4->setCity('Chevreuse');
        $capture4->setStatus('validated');
        $capture4->setUser($user2);
        $capture4->setBird($bird);
        $capture4->setValidatedBy($user3);

        $comment1 = new Comment();
        $comment1->setAuthor($user1);
        $comment1->setContent('Belle découverte !');
        $comment1->setCreatedAt(new \DateTime('now'));
        $comment1->setPublished(true);

        $comment2 = new Comment();
        $comment2->setAuthor($user2);
        $comment2->setContent('Je pensais pas trouver cette espèce dans cette région !');
        $comment2->setCreatedAt(new \DateTime('now'));
        $comment2->setPublished(false);

        $comment3 = new Comment();
        $comment3->setAuthor($user3);
        $comment3->setContent('Très professionnel cette observation.');
        $comment3->setCreatedAt(new \DateTime('now'));
        $comment3->setPublished(true);

        $manager->persist($user1);
        $manager->persist($user2);
        $manager->persist($user3);
        $manager->persist($user4);
        $manager->persist($capture1);
        $manager->persist($capture2);
        $manager->persist($capture3);
        $manager->persist($capture4);
        $manager->persist($comment1);
        $manager->persist($comment2);
        $manager->persist($comment3);

        $manager->flush();
    }
}