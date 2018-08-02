<?php
/**
 * Created by PhpStorm.
 * User: seymos
 * Date: 02/08/18
 * Time: 18:59
 */

namespace App\Services;


use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Extension\Validator\Constraints\FormValidator;
use Symfony\Component\Form\FormBuilder;

class FormManager
{
    private $entityManager;

    private $formValidator;

    public function __construct(EntityManager $entityManager, FormValidator $formValidator)
    {
        $this->entityManager = $entityManager;
        $this->formValidator = $formValidator;
    }
}