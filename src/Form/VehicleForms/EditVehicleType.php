<?php

namespace App\Form;

use App\Entity\Model;
use App\Entity\RegistrationCertificate;
use App\Entity\User;
use App\Entity\Vehicle;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

class EditVehicleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
            ->add('extraMileageRate')
            ->add('pricePerDay', MoneyType::class, [
                'label' => 'Tarif/jour',
                'currency' => 'EUR',  // Ou une autre devise si nécessaire
                'scale' => 2,  // Gère les décimales
            ])
            ->add('address')
            ->add('postalCode')
            ->add('city')
            ->add('description')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vehicle::class,
        ]);
    }
}
