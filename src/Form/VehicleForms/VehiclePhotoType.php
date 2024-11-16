<?php

namespace App\Form\VehicleForms;

use App\Entity\Vehicle;
use App\Entity\VehicleCondition;
use App\Entity\VehiclePhoto;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Validator\Constraints as Assert;

class VehiclePhotoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('imageFile', VichImageType::class, [
                'label' => 'Photo du véhicule',
                'required' => true,
                'allow_delete' => false,
                'download_uri' => false,
                'attr' => [
                    'accept' => 'image/webp, image/png, image/jpeg, image/jpg',
                    'class' => 'image-upload',
                ],
                'constraints' => [
                    new Assert\Image([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/webp',
                            'image/png',
                            'image/jpg',
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image au format JPEG, PNG, JPG ou WEBPpp.',
                    ]),
                ],

            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => VehiclePhoto::class,
        ]);
    }
}
