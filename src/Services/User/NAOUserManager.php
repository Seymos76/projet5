<?php

// src/Services/User/NAOUserManager.php

namespace App\Services\User;

use App\Services\NAOManager;
use App\Entity\User;

class NAOUserManager extends NAOManager
{
    /**
     * @param User $user
     * @return null|string
     */
	public function getRoleFR(User $user): ?string
	{
		$roles = $user->getRoles();
		if (in_array('ROLE_ADMIN', $roles)) {
		    return "Administrateur";
        } elseif (in_array('ROLE_NATURALIST', $roles)) {
		    return "Naturaliste";
        } else {
		    return "Particulier";
        }
        /*if (in_array('naturalist', $roles))
        {
            $userRole = 'Naturaliste';
        }
        elseif (in_array('administrator', $roles))
        {
            $userRole = 'Administrateur';
        }
        elseif (in_array('particular', $roles))
        {
           $userRole = 'Particulier';
        }*/

        //return $userRole;
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
}