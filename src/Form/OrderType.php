<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Carrier;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        //dd($options);
        //pour que mon form n'envoie que les adresse liés au user connecté
        $user = $options['user'];
        $builder
            ->add('addresses', EntityType::class, [
                'label' => 'Choisissez votre adresse',
                'required' => true,
                'class' => Address::class,
                'choices' => $user->getAddresses(),
                'multiple' => false,
                'expanded' => true,
            ])

            ->add('carrier', EntityType::class, [
                'label' => 'Choisissez votre Transporteur',
                'class' => Carrier::class,
                'required' => true,
                'multiple' => false,
                'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'user' => array()
        ]);
    }
}
