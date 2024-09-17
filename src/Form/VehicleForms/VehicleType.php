<?php

namespace App\Form;

use App\Entity\Location;
use App\Entity\Model;
use App\Entity\Status;
use App\Entity\User;
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
            ->add('RegistrationCertificate', RegistrationCertificateType::class,
            ['label'=> false])

            ->add('model', ModelType::class,
            ['label' => false],)

            ->add('location', LocationType::class,
            ['label'=> false])

            ->add('fuel', FuelType::class,
            ['label'=> false])
            
            ->add('year')
            ->add('mileage')
            ->add('description')
            ->add('color')
            ->add('mileageAllowance')
            ->add('extraMileageRate')
            // ->add('model', EntityType::class, [
            //     'class' => Model::class,
            //     'choice_label' => 'id',
            // ])
            // ->add('location', EntityType::class, [
            //     'class' => Location::class,
            //     'choice_label' => 'id',
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vehicle::class,
        ]);
    }
}
