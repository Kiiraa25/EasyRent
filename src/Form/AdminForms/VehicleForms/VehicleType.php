<?php

namespace App\Form\AdminForms;

use App\Entity\Vehicle;
use Symfony\Component\Form\AbstractType;
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
            ->add('extraMileageRate', IntegerType::class, [
                'label' => 'Tarif supplémentaire/km',
            ])
            ->add('pricePerDay', MoneyType::class, [
                'label' => 'Tarif/jour',
                // 'currency' => 'EUR',
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vehicle::class,
        ]);
    }
}
