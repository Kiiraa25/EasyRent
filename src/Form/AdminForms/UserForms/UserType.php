<?php

namespace App\Form\AdminForms\UserType;

use App\Entity\User;
use App\Form\AdminForms\UserForms\UserProfileType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Utilisateur' => 'ROLE_USER',
                    'Admin' => 'ROLE_ADMIN',
                ],
                'expanded' => true,
                'multiple' => true,
                'label' => 'Rôles',
            ])
            // Inclure le sous-formulaire sans options spécifiques à EntityType
            ->add('profile', UserProfileType::class, [
                'label' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
