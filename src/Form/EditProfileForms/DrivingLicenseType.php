<?php

namespace App\Form;

use App\Entity\DrivingLicense;
use App\Entity\Request;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Vich\UploaderBundle\Form\Type\VichImageType;


class DrivingLicenseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

        ->add('licenseNumber',TextType::class, [
            'label'=> 'Numéro de permis',
            'attr' => [
                'class' => 'suscribe-input',
                'placeholder' => 'Numéro de permis'
            ]
            ])

            ->add('issueDate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date d"obtention',
                'attr' => [
                    'class' => 'suscribe-input',
                    'placeholder' => 'Date d"obtention'
                ]
            ])

            ->add('expiryDate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'date d"expiration',
                'attr' => [
                    'class' => 'suscribe-input',
                    'placeholder' => 'date d"expiration'
                ]
            ])

            ->add('countryOfIssue',TextType::class, [
                'label'=> 'Pays de délivrence',
                'attr' => [
                    'class' => 'suscribe-input',
                    'placeholder' => 'Pays de délivrence'
                ]
                ])

                ->add('frontImageFile', VichImageType::class, [
                    'label' => 'Photo recto',
                    'required' => false,
                    'allow_delete' => false,
                    'download_uri' => false,
                    'attr' => [
                        'accept' => 'image/*',
                        'class' => ''
                    ]
                ])
                ->add('backImageFile', VichImageType::class, [
                    'label' => 'Photo verso',
                    'required' => false,
                    'allow_delete' => false,
                    'download_uri' => false,
                    'attr' => [
                        'accept' => 'image/*',
                        'class' => ''
                    ]
                ])
                
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DrivingLicense::class,
        ]);
    }
}
