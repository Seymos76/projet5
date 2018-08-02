<?php
/**
 * Created by PhpStorm.
 * User: seymos
 * Date: 02/08/18
 * Time: 18:50
 */

namespace App\Form;


use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'avatar',
                FileType::class,
                array(
                    'required' => false
                )
            )
            ->add(
                'lastname',
                TextType::class
            )
            ->add(
                'firstname',
                TextType::class
            )
            ->add(
                'email',
                EmailType::class
            )
            ->add(
                'username',
                TextType::class,
                array(
                    'required' => false
                )
            )
            ->add(
                'password',
                PasswordType::class
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}