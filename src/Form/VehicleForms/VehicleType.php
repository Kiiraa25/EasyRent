<?php

namespace App\Form\VehicleForms;


use App\Enum\FuelTypeEnum;
use App\Enum\GearBoxTypeEnum;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use App\Entity\Vehicle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class VehicleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'model',
                ModelType::class,
                ['label' => false],
            )

            ->add(
                'RegistrationCertificate',
                RegistrationCertificateType::class,
                ['label' => false]
            )

            ->add('fuelType', EnumType::class, [
                'class' => FuelTypeEnum::class
            ])

            ->add('gearboxType', EnumType::class, [
                'class' => GearBoxTypeEnum::class
            ])

            ->add('doors', IntegerType::class, [
                'label' => 'Nombre de portes',
                'constraints' => [
                    new Assert\GreaterThanOrEqual([
                        'value' => 1,
                        'message' => 'Le nombre de portes doit être au moins de 1.'
                    ]),
                    new Assert\LessThanOrEqual([
                        'value' => 5,
                        'message' => 'Le nombre de portes doit être au maximum de 5.'
                    ]),
                ],
            ])
        
            ->add('seats', IntegerType::class, [
                'label' => 'Nombre de sièges',
                'constraints' => [
                    new Assert\GreaterThanOrEqual([
                        'value' => 1,
                        'message' => 'Le nombre de sièges doit être au moins de 1.'
                    ]),
                    new Assert\LessThanOrEqual([
                        'value' => 7,
                        'message' => 'Le nombre de sièges doit être au maximum de 7.'
                    ]),
                ],
            ])
        
            ->add('mileage', IntegerType::class, [
                'label' => 'Kilométrage',
                'constraints' => [
                    new Assert\GreaterThanOrEqual([
                        'value' => 0,
                        'message' => 'Le kilométrage doit être au minimum de 0 km.'
                    ]),
                    new Assert\LessThanOrEqual([
                        'value' => 200000,
                        'message' => 'Le kilométrage doit être au maximum de 200 000 km.'
                    ]),
                ],
            ])

            ->add('description', TextType::class, [
                'label' => 'Description',
            ])
            ->add('color', TextType::class, [
                'label' => 'Couleur',
            ])
            ->add('extraMileageRate', MoneyType::class, [
                'label' => 'Tarif supplémentaire/km',
                'currency' => false,
                'scale' => 2,
            ])
            ->add('pricePerDay', MoneyType::class, [
                'label' => 'Tarif/jour',
                'currency' => false,
                'scale' => 2,
            ])

            ->add('address', TextType::class, [
                'label' => 'Adresse',
            ])

            ->add('postalCode', IntegerType::class, [
                'label' => 'Code postal',
            ])
            ->add('city', TextType::class, [
                'attr' => [
                    'data-action' => 'address-input'
                ]
            ])
            ->add('photos', CollectionType::class, [
                'entry_type' => VehiclePhotoType::class,  // Utilise le formulaire VehiclePhotoType pour chaque photo
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => false, //'Photos du véhicule (5 minimum)',
                'prototype' => true,
                'constraints' => [
                    new Assert\Count([
                        'min' => 5,
                        'minMessage' => 'Vous devez télécharger au moins 5 photos.',
                    ]),
                ],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vehicle::class,
        ]);
    }
}
