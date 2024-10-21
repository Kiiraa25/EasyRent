<?php

namespace App\Form;

use App\Dto\SearchDto;
use App\Enum\FuelTypeEnum;
use App\Enum\GearboxTypeEnum;
use App\Enum\VehicleCategoryEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
       

        $builder
            ->add('search', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'class' => 'suscribe-input',
                    'placeholder' => 'Adresse précise, gare, métro...'
                ]
            ])

            ->add('startDate', DateType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'class' => 'suscribe-input',
                    'placeholder' => 'Date de début'
                ]
            ])
            ->add('endDate', DateType::class,[
                'required' => true,
                'label' => false,
                'attr' => [
                    'class' => 'suscribe-input',
                    'placeholder' => 'Date de fin'
                ]
            ])

            ->add('vehicleCategory', EnumType::class, [
                'class' => VehicleCategoryEnum::class,
                'required' => false,
                'label' => false,
                'placeholder' => 'Type de véhicule',
                'attr' => [
                    'class' => 'suscribe-input'
                    
                ]
            ])
            ->add('gearboxType', EnumType::class, [
                'class' => GearboxTypeEnum::class,
                'required' => false,
                'label' => false,
                'placeholder' => 'Boite de vitesse',
                'attr' => [
                    'class' => 'suscribe-input'
                    
                ]
            ])

            ->add('fuelType', EnumType::class, [
                'class' => FuelTypeEnum::class,
                'choice_label' => 'name',
                'required' => false,
                'label' => false,
                'placeholder' => 'Type de carburant',
                'attr' => [
                    'class' => 'suscribe-input'
                ]
            ])
            ->add('totalPrice', IntegerType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'class' => 'suscribe-input',
                    'placeholder' => 'Prix total'
                ]
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'data_class' => SearchDto::class,
            'startDate' => null,
            'endDate' => null,
        ]);
    }
}
