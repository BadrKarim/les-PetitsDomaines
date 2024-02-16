<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de l\'adresse',
                'attr' => [
                    'placeholder' => 'Nommez votre adresse'
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Votre prénom',
                'attr' => [
                    'placeholder' => 'Ajoutez Votre prénom'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Voter nom',
                'attr' => [
                    'placeholder' => 'Ajoutez votre nom'
                ]
            ])
            ->add('company', TextType::class, [
                'label' => 'Nom de votre société',
                'required' => false,
                'attr' => [
                    'placeholder' => '(facultatif) Ajoutez le nom de votre société'
                ]
            ])
            ->add('address', TextType::class, [
                'label' => 'Votre adresse',
                'attr' => [
                    'placeholder' => '1 rue dupont ...'
                ]
            ])
            ->add('postal', TextType::class, [
                'label' => 'Votre code postale',
                'attr' => [
                    'placeholder' => 'Ajoutez votre code postale'
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'Votre ville',
                'attr' => [
                    'placeholder' => 'Ajoutez votre ville'
                ]
            ])
            ->add('country', CountryType::class, [
                'label' => 'Pays ',
                'attr' => [
                    'placeholder' => 'Nommez votre adresse'
                ]
            ])
            ->add('phone', TelType::class, [
                'label' => 'Numéro de telephone',
                'attr' => [
                    'placeholder' => 'Ajoutez votre téléphone'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
                'attr' => [
                    'class' => 'btn-block btn-info'
                ]

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
