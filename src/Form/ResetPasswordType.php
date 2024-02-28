<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('new_password', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'le mot de passe de confirmation doivent être identique',
            'required' => true,
            'first_options' => [
                'label' => 'Nouveau mot de passe',
                'attr' => [
                    'placeholder' => 'Veuillez saisir votre nouveau mot de passe'
                ]
            ],
            'second_options' => [
                'label' => 'Confirmez votre mot de passe',
                'attr' => [
                    'placeholder' => 'Veuillez saisir de nouveau votre nouveau mot de passe'
                ]
            ]
        ])

        ->add('submit', SubmitType::class, [
            'label' => "Enregistrer"
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
