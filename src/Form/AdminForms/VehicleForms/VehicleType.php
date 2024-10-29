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
                'attr' => ['class' => 'edit-input'],
            ])
            ->add('doors', IntegerType::class, [
                'label' => 'Nombre de portes',
                'attr' => ['class' => 'edit-input'],
            ])
            ->add('seats', IntegerType::class, [
                'label' => 'Nombre de sièges',
                'attr' => ['class' => 'edit-input'],
            ])
            ->add('description', TextType::class, [
                'label' => 'Description',
                'attr' => ['class' => 'edit-input'],
            ])
            ->add('color', TextType::class, [
                'label' => 'Couleur',
                'attr' => ['class' => 'edit-input'],
            ])
            ->add('extraMileageRate', IntegerType::class, [
                'label' => 'Tarif supplémentaire/km',
                'attr' => ['class' => 'edit-input'],
            ])
            ->add('pricePerDay', MoneyType::class, [
                'label' => 'Tarif/jour',
                // 'currency' => 'EUR',
                'scale' => 2,
                'attr' => ['class' => 'edit-input'],
            ])

            ->add('address', TextType::class, [
                'label' => 'Adresse',
                'attr' => ['class' => 'edit-input'],
            ])

            ->add('postalCode', IntegerType::class, [
                'label' => 'Code postal',
                'attr' => ['class' => 'edit-input'],
            ])
            ->add('city', TextType::class, [
                'attr' => [
                    'data-action' => 'address-input',
                    'class' => 'edit-input'
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
