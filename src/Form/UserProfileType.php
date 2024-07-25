<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\UserProfile;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use App\Form\DrivingLicenseType;

use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

        ->add('lastName', TextType::class, [
            'label' => false,
            'attr' => [
                'class' => 'suscribe-input',
                'placeholder' => 'Nom'
            ]
        ])

        ->add('firstName', TextType::class, [
            'label' => false,
            'attr' => [
                'class' => 'suscribe-input',
                'placeholder' => 'Prénom'
            ]
        ])

        ->add('birthDate', DateType::class, [
            'widget' => 'single_text',
            'label' => false,
            'attr' => [
                'class' => 'suscribe-input',
                'placeholder' => 'Birth Date'
            ]
        ])
        
            ->add('address', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'suscribe-input',
                    'placeholder' => 'adresse'
                ]
            ])
            ->add('postalCode', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'suscribe-input',
                    'placeholder' => 'Code postal'
                ]
            ])
            ->add('city', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'suscribe-input',
                    'placeholder' => 'Ville'
                ]
            ])
            ->add('phone', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'suscribe-input',
                    'placeholder' => 'Téléphone'
                ]
            ])
            ->add('picture', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'suscribe-input',
                    'placeholder' => 'Photo'
                ]
            ])

            ->add('drivingLicense', DrivingLicenseType::class, [
                'label' => false,
            ]);
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserProfile::class,
        ]);
    }
}
