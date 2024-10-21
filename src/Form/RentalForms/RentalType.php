<?php

namespace App\Form;

use Amp\Http\Client\Interceptor\ModifyRequest;
use App\Entity\PaymentMethod;
use App\Entity\Rental;
use App\Enum\PaymentMethodEnum;
use App\Enum\RentalStatusEnum;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use App\Entity\User;
use App\Entity\Vehicle;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RentalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('startDate', DateType::class, [
            'data' => $options['startDate'] ?? new \DateTime(),
            'required' => true,
        ])
        ->add('endDate', DateType::class, [
            'data' => $options['endDate'] ?? (clone $options['startDate'])->modify('+7 days'),
            'required' => true,
        ])

            ->add('paymentMethod', EnumType::class, [
                'class' => PaymentMethodEnum::class
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rental::class,
            'startDate' => null,
            'endDate' => null,
        ]);
    }
}
