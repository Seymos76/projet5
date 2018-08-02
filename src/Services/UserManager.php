<?php
/**
 * Created by PhpStorm.
 * User: seymos
 * Date: 02/08/18
 * Time: 20:34
 */

namespace App\Services;


use App\Entity\User;
use Cocur\Slugify\Slugify;

class UserManager
{
    public function validateUserRegistration(User $user)
    {
        // vérifier username
        if (!$user->getUsername()) {
            $user->setUsername(self::slugifyThis($user->getFirstname()));
        }
    }

    public function slugifyThis($data)
    {
        $slugify = new Slugify();
        $slugified = $slugify->slugify($data);
        return $slugified;
    }
}