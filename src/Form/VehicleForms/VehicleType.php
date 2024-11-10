<?php

namespace App\Form\VehicleForms;

use App\Entity\Location;
use App\Entity\Model;
use App\Entity\Status;
use App\Entity\User;
use App\Enum\FuelType;
use App\Enum\GearboxType;
use App\Enum\FuelTypeEnum;
use App\Enum\GearBoxTypeEnum;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use App\Entity\Vehicle;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Vich\UploaderBundle\Form\Type\VichImageType;

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
                'enum_class' => GearBoxTypeEnum::class
            ])

            ->add('mileage', IntegerType::class, [
                'label' => 'Kilométrage',
            ])

            ->add('doors', IntegerType::class, [
                'label' => 'Nombre de portes',
            ])

            ->add('seats', IntegerType::class, [
                'label' => 'Nombre de sièges',
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
                // 'constraints' => [
                //     new Count([
                //         'min' => 5,
                //         'minMessage' => 'Vous devez télécharger au moins {{ limit }} photos.',
                //     ]),
                // ],
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
