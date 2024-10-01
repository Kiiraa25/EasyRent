<?php

namespace App\Form;

use App\Entity\Location;
use App\Entity\Model;
use App\Entity\Status;
use App\Entity\User;
use App\Enum\FuelType;
use App\Enum\GearboxType;
use App\Enum\FuelTypeEnum;
use App\Enum\GearboxTypeEnum;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use App\Entity\Vehicle;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
                'class' => GearboxTypeEnum::class
            ])

            ->add('mileage')
            ->add('doors')
            ->add('seats')
            ->add('description')
            ->add('color')
            ->add('mileageAllowance')
            ->add('extraMileageRate')
            ->add('pricePerDay')

            ->add('address')
            ->add('postalCode')
            ->add('city')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vehicle::class,
        ]);
    }
}
