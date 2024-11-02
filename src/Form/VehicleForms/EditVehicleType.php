<?php

namespace App\Form;

use App\Entity\Vehicle;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class EditVehicleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('extraMileageRate', MoneyType::class, [
                'label' => 'Prix par km supplémentaire',
                'currency' => false,
                'scale' => 2,
                'attr' => ['class' => 'edit-input']
            ])
            ->add('pricePerDay', MoneyType::class, [
                'label' => 'Tarif/jour',
                'currency' => false,
                'scale' => 2,
                'attr' => ['class' => 'edit-input']
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse',
                'attr' => ['class' => 'edit-input']
            ])
            ->add('postalCode', IntegerType::class, [
                'label' => 'Code postal',
                'attr' => ['class' => 'edit-input']
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'attr' => ['class' => 'edit-input']
            ])
            ->add('description', TextType::class, [
                'label' => 'Description de l\'annonce',
                'attr' => ['class' => 'edit-input']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vehicle::class,
        ]);
    }
}
