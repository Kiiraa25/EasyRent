<?php

namespace App\Form;

use App\Entity\RegistrationCertificate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class RegistrationCertificateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('issueDate', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('certificateNumber', TextType::class, [
                'label' => 'Numéro de certificat',
            ])
            ->add('countryOfIssue', TextType::class, [
                'label' => 'Pays d\'émission',
            ])
            ->add('frontImageFile', VichImageType::class, [
                'label' => 'Photo recto',
                'required' => true,
                'allow_delete' => false,
                'mapped' => true,
                'download_uri' => false,
                'attr' => [
                    'accept' => 'image/*',
                    'class' => ''
                ]
            ])
            ->add('backImageFile', VichImageType::class, [
                'label' => 'Photo verso',
                'required' => false,
                'allow_delete' => false,
                'mapped' => true,
                'download_uri' => false,
                'attr' => [
                    'accept' => 'image/*',
                    'class' => ''
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RegistrationCertificate::class,
        ]);
    }
}
