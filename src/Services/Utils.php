<?php
/**
 * Created by PhpStorm.
 * User: seymos
 * Date: 04/08/18
 * Time: 10:02
 */

namespace App\Services;


use Cocur\Slugify\Slugify;

class Utils
{
    public function slugifyThis($data)
    {
        $slugify = new Slugify();
        $slugified = $slugify->slugify($data);
        return $slugified;
    }
}