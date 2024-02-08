<?php

namespace App\Form\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class PasswordForgetFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'Nouveau mot de passe',
                    'attr' => [
                        'class' => 'form-control form-control-lg',
                        'placeholder'=> 'Nouveau mot de passe'
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirmez le nouveau mot de passe',
                    'attr' => [
                        'class' => 'form-control form-control-lg',
                        'placeholder'=> 'Confirmez le nouveau mot de passe'
                    ]
                ],
                'invalid_message' => 'Les champs de mot de passe doivent correspondre.',
            ]);
    }
}
