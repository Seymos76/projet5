<?php

// src/Services/User/NAOUserManager.php

namespace App\Services\User;

use App\Services\NAOManager;
use App\Entity\User;

class NAOUserManager extends NAOManager
{
	public function getRoleFR($user)
	{
		$roles = $user->getRoles();

        if (in_array('naturalist', $roles))
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
        }

        return $userRole;
	}

	public function getNaturalistOrParicularRole($user)
	{
		$roles = $user->getRoles();

        if ((in_array('naturalist', $roles)) or (in_array('administrator', $roles)))
        {
            $userRole = 'naturalist';
        }
        elseif (in_array('particular', $roles))
        {
           $userRole = 'particular';
        }

        return $userRole;
	}
}