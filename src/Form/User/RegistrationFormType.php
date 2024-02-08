<?php

namespace App\Form\User;

use App\Entity\User\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Prénoms *',
                'attr' => [
                    'placeholder' => 'Prénoms',
                    'class'=> 'form-control form-control-lg'
                ]
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom *',
                'attr' => [
<<<<<<< HEAD
                    'placeholder' => 'Nom',
                    'class'=> 'form-control form-control-lg'
                ]
            ])
            ->add('dob', DateType::class, [
                'label'=>'Date de naissance *',
=======
                    'placeholder' => 'Nom'
                ]
            ])
            ->add('dob', DateType::class, [
                'label' => 'Date de naissance',
>>>>>>> 29338cb1d687e4659155db8db790ebcc07ae01ab
                'widget' => 'single_text',
                'attr' => [
                    'data-format' => 'yyyy-mm-dd',
                    'class'=> 'form-control form-control-lg'
                ],
            ])
            ->add('gender', ChoiceType::class, [
                'label' => 'Genre *',
                'attr' => [
                    'class'=> 'form-control form-control-lg'
                ],
                'choices' => [
                    'Homme' => 'Homme',
                    'Femme' => 'Femme'
                ],
                'expanded' => false
            ])
            ->add('phone1', TextType::class, [
                'label' => 'N° de téléphone 1 (whatsapp) *',
                'attr' => [
                    'placeholder' => 'N° de téléphone 1 (whatsapp)',
                    'class'=> 'form-control form-control-lg'
                ]
            ])
            ->add('phone2', TextType::class, [
                'label' => 'N° de téléphone 2',
                'required' => false,
                'attr' => [
                    'placeholder' => 'N° de téléphone 2',
                    'class'=> 'form-control form-control-lg'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse email *',
                'attr' => [
                    'placeholder' => 'Adresse email',
                    'class'=> 'form-control form-control-lg'
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label'=>'Accepter les conditions *',
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter nos conditions.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'label'=>'Mot de passe *',
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'placeholder'=>'Mot de passe',
                    'class'=> 'form-control form-control-lg'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un nouveau mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit avoir au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
