<?php

namespace App\Form;

use App\Entity\user;
use App\Entity\UserProfile;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('address', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'suscribe-input',
                    'placeholder' => 'Addresse'
                ]
                ])

            ->add('postalCode', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'suscribe-input',
                    'placeholder' => 'Postal Code'
                ]
            ])

            ->add('city', TextType::class, [
                'label' => false, // Disable the label
                'attr' => [
                    'class' => 'suscribe-input',
                    'placeholder' => 'City'
                ]
            ])

    
            ->add('phone', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'suscribe-input',
                    'placeholder' => 'Phone'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserProfile::class,
        ]);
    }
}
