<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints as Assert;



class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

        ->add('profile', NameUserProfileType::class)

        ->add('email', EmailType::class, [
            'required' => true,
            'label' => false,
            'attr' => [
                'class' => 'suscribe-input',
                'placeholder' => 'Email'
            ],
            'constraints' => [
                new Assert\NotBlank(),
                new Assert\Email()
            ],
        ])

        ->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'mapped' => false,
            'invalid_message' => 'The password fields must match.',
            'required' => true,
            'first_options'  => [
                'label' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'placeholder' => 'Mot de passe *',
                    'class' => 'suscribe-input'
                ]
            ],
            'second_options' => [
                'label' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'placeholder' => 'Répéter mot de passe *',
                    'class' => 'suscribe-input'
                ]
            ],
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez insérer un mot de passe',
                ]),
                new Length([
                    'min' => 8,
                    'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                    'max' => 4096,
                ]),
                new Assert\Regex([
                    'pattern' => '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).+$/',
                    'message' => 'Votre mot de passe doit contenir au moins une majuscule, une minuscule, un chiffre et un caractère spécial.',
                ]),
            ],
        ])
        

        ->add('agreeTerms', CheckboxType::class, [
            'mapped' => false,
            'label' => "J'accepte les <a href='/CGU' target='_blank'>Conditions Générales d'Utilisation</a>",
            'label_html' => true,  // Active l’interprétation HTML dans le label
            'label_attr' => ['class' => 'cgu-label'],
            'constraints' => [
                new IsTrue([
                    'message' => 'Vous devez accepter les Conditions Générales d\'Utilisation.',
                ]),
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
