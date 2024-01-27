<?php

namespace App\Form\User;

use App\Entity\User\Address;
use App\Entity\User\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('country', ChoiceType::class, [
                'label' => 'Genre',
                'choices' => [
                    "Côte d'Ivoire" => 'ci',
                    "France" => 'fr'
                ],
                'expanded' => false
            ])
            ->add('city', TextType::class, [
                'label'=>'Ville',
                'attr'=>[
                    'placeholder'=>'Ville'
                ]
            ])
            ->add('zipCode', TextType::class, [
                'label'=>'Code postal',
                'required'=>false,
                'attr'=>[
                    'placeholder'=>'Code postal'
                ]
            ])
            ->add('street', TextType::class, [
                'label'=>'Rue',
                'required'=>false,
                'attr'=>[
                    'placeholder'=>'Rue'
                ]
            ])
            ->add('furtherInformation', TextareaType::class, [
                'label'=>'Information complémentatire',
                'required'=>false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
