<?php

namespace App\Form;

use App\Entity\UserProfile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use App\Form\DrivingLicenseType;
use Vich\UploaderBundle\Form\Type\VichImageType;

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
            ->add('country', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'suscribe-input',
                    'placeholder' => 'Pays'
                ]
            ])
            ->add('phone', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'suscribe-input',
                    'placeholder' => 'Téléphone'
                ]
            ])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Photo de profil',
                'required' => false,
                'allow_delete' => false,
                'download_uri' => false,
                'attr' => [
                    'accept' => 'image/*',
                    'class' => 'profile-picture-input'
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
