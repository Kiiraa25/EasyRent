<?php

namespace App\Form\AdminForms\RentalForms;

use App\Entity\Rental;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Enum\PaymentMethodEnum;
use Doctrine\DBAL\Types\FloatType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;

class RentalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('startDate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de début',
                'attr' => ['class' => 'edit-input'],
            ])
            ->add('endDate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de fin',
                'attr' => ['class' => 'edit-input'],
            ])
            ->add('paymentMethod', EnumType::class, [
                'class' => PaymentMethodEnum::class,
                'attr' => ['class' => 'edit-input'],
            ])
            ->add('totalPrice', IntegerType::class, [
                'label' => 'Prix total',
                'attr' => ['class' => 'edit-input'],
            ])
            ->add('mileageLimit', IntegerType::class, [
                'label' => 'Limite de kilométrage',
                'attr' => ['class' => 'edit-input'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rental::class,
        ]);
    }
}
