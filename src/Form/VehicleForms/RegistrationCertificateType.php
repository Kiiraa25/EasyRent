<?php

namespace App\Form;

use App\Entity\RegistrationCertificate;
use App\Entity\Request;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Vich\UploaderBundle\Form\Type\VichImageType;

class RegistrationCertificateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('issueDate', null, [
                'widget' => 'single_text',
            ])
            ->add('certificateNumber')
            ->add('countryOfIssue')
            ->add('frontImageFile', VichImageType::class, [
                'label' => 'Photo recto',
                'required' => false,
                'allow_delete' => false,
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
                'download_uri' => false,
                'attr' => [
                    'accept' => 'image/*',
                    'class' => ''
                ]
            ])
            // ->add('request', EntityType::class, [
            //     'class' => Request::class,
            //     'choice_label' => 'id',
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RegistrationCertificate::class,
        ]);
    }
}
