<?php

namespace App\Form;

use App\Entity\Brand;
use App\Entity\Fuel;
use App\Entity\Model;
// use App\Entity\VehicleCategoryType;
use App\Entity\VehicleType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('vehicleCategory', VehicleCategoryType::class)
            ->add('brand', BrandType::class)
            ->add('fuel', FuelType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Model::class,
        ]);
    }
}
