<?php

// src/Services/User/NAOUserManager.php

namespace App\Services\User;

use App\Services\NAOManager;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class NAOUserManager extends NAOManager
{
    private $container;

    public function __construct(EntityManagerInterface $em, ContainerInterface $container)
    {
        parent::__construct($em);
        $this->container = $container;
    }

    /**
     * @return null|string
     */
	public function getRoleFR($user): ?string
	{
		$roles = $user->getRoles();
		if (in_array('ROLE_ADMIN', $roles)) {
		    return "administrateur";
        } elseif (in_array('ROLE_NATURALIST', $roles)) {
		    return "naturaliste";
        } else {
		    return "particulier";
        }
	}

    /**
     * @param User $user
     * @param $biography
     */
	public function changeBiography(User $user, $biography)
    {
        $user->setBiography($biography);
        $this->getContainer()->get('app.nao_manager')->addOrModifyEntity($user);
        $this->getContainer()->get('session')->getFlashBag()->add('success', "Votre biographie a été changée avec succès !");
    }

    /**
     * @param User $user
     * @return null|string
     */
	public function getNaturalistOrParticularRole(User $user): ?string
	{
		$roles = $user->getRoles();
        if ((in_array('ROLE_ADMIN', $roles)) or (in_array('ROLE_NATURALIST', $roles)))
        {
            return 'naturalist';
        }
        elseif (in_array('ROLE_USER', $roles))
        {
           return 'particular';
        }
	}

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }
}
