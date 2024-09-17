<?php


// formulaire utilisé pour intégrer le nom + prenom (de la table userprofile) dans le formulaire d'inscription lié à la table user

namespace App\Form;

use App\Entity\UserProfile;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

use Symfony\Component\Form\Extension\Core\Type\TextType;

class NameUserProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

        ->add('lastName', TextType::class, [
            'label' => false,
            'attr' => [
                'class' => 'suscribe-input',
                'placeholder' => 'Nom'
            ]
        ])

        ->add('firstName', TextType::class, [
            'label' => false,
            'attr' => [
                'class' => 'suscribe-input',
                'placeholder' => 'Prénom'
            ]
        ])

        ->add('birthDate', DateType::class, [
            'widget' => 'single_text',
            'label' => false,
            'attr' => [
                'class' => 'suscribe-input',
                'placeholder' => 'Birth Date'
            ]
        ])
         
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserProfile::class,
        ]);
    }
}