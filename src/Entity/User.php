<?php

namespace App\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
<<<<<<< HEAD
class User implements UserInterface, \Serializable
=======
class User implements UserInterface
>>>>>>> 8f51665e4a72d4a1361dc55d5a0c736f5236a534
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=254, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @ORM\Column(name="roles", type="array")
     */
    private $roles;

    /**
     * @ORM\Column(name="lastname", type="string")
     */
    private $lastname;

    /**
     * @ORM\Column(name="firstname", type="string")
     */
    private $firstname;

    /**
     * @ORM\Column(name="date_register", type="datetime")
     */
    private $date_register;

    /**
     * @ORM\Column(name="token", type="string", unique=true, nullable=true)
     */
    private $token;

    /**
     * @ORM\Column(name="activation_code", type="string", unique=true, nullable=true)
     */
    private $activation_code;

    /**
     * @ORM\Column(name="account_type", type="string", nullable=true)
     */
    private $account_type;

    /**
     * @ORM\Column(name="avatar", type="string", nullable=true)
     * @Assert\File(mimeTypes={ "image/png", "image/jpeg", "image/jpg", "image/gif" })
     */
    private $avatar;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="user", orphanRemoval=true)
     */
    private $captures;

    public function __construct()
    {
        $this->active = false;
        $this->roles = array("ROLE_USER");
        $this->date_register = new \DateTime('now');
<<<<<<< HEAD
        $this->activation_code = md5(uniqid('code_', false));
=======
        $this->captures = new ArrayCollection();
>>>>>>> 8f51665e4a72d4a1361dc55d5a0c736f5236a534
    }

    public function getUsername() :string
    {
        return $this->username;
    }

    public function setUsername($username) :void
    {
        $this->username = $username;
    }

    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function getActive() :bool
    {
        return $this->active;
    }

    public function setActive($active) :void
    {
        $this->active = $active;
    }

    public function getRoles() :array
    {
        return $this->roles;
    }

    public function setRole($role) :void
    {
        if (!in_array($this->roles, $role)) {
            $this->roles[] = $role;
        }
    }

    public function addRole($role) :void
    {
        if (!in_array($role, $this->roles)) {
            $this->roles[] = $role;
        }
    }

    public function removeRole($role) :array
    {
        if (in_array($role, $this->roles)) {
            unset($role);
            return $this->roles;
        }
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     */
    public function setLastname($lastname): void
    {
        $this->lastname = $lastname;
    }

    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * @return mixed
     */
    public function getDateRegister()
    {
        return $this->date_register;
    }

    /**
     * @param mixed $date_register
     */
    public function setDateRegister($date_register): void
    {
        $this->date_register = $date_register;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token): void
    {
        $this->token = $token;
    }

    public function getActivationCode() :string
    {
        return $this->activation_code;
    }

    public function setActivationCode($activation_code) :void
    {
        $this->activation_code = $activation_code;
    }

    public function getAccountType()
    {
        return $this->account_type;
    }

    public function setAccountType($account_type) :void
    {
        $this->account_type = $account_type;
    }

    /**
     * @return mixed
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param mixed $avatar
     */
    public function setAvatar($avatar): void
    {
        $this->avatar = $avatar;
    }

<<<<<<< HEAD
=======
    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

>>>>>>> 8f51665e4a72d4a1361dc55d5a0c736f5236a534
    public function eraseCredentials()
    {
    }

<<<<<<< HEAD
    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized, array('allowed_classes' => false));
=======
    /**
     * @return Collection|Capture[]
     */
    public function getCaptures()
    {
        return $this->captures;
    }

    public function addCapture(Capture $capture): self
    {
        $this->captures[] = $capture;
        
        $capture->setUser($this);

        return $this;
    }

    public function removeCapture(Capture $capture): self
    {
        $this->captures->removeElement($capture);
>>>>>>> 8f51665e4a72d4a1361dc55d5a0c736f5236a534
    }
}
