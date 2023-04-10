<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\Length;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Votre prénom',
                'constraints' => new Length([
                    'min'=> 2,
                    'max'=> 50
                ]),
                'attr' => [
                    'placeholder' => 'Ex : Dimitri'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Votre nom',
                'constraints' => new Length([
                    'min'=> 2,
                    'max'=> 50
                ]),
                'attr' => [
                    'placeholder' => 'Ex : Dupond'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Votre email',
                'constraints' => new Length([
                    'min'=> 2,
                    'max'=> 50
                ]),
                'attr' => [
                    'placeholder' => 'dimitri.dupond@exemple.com'
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Le mot de passe et la confirmation doivent être identique.',
                'label' => 'Votre mot de passe',
                'required' => true,
                'first_options' => [ 
                    'label' => 'Mot de passe',
                    'attr' => [
                        'placeholder'=>'Ex : JâIF@1M'
                    ]
                ],
                'second_options' => [ 
                    'label' => 'Confirmez votre mot de passe', 
                    'attr' => [
                        'placeholder'=>'Ex : JâIF@1M'
                    ]
                ]
            ])
            ->add('guests', IntegerType::class, [
                'label' => 'Le nombre de convives',
                'attr' => [
                    'min' => 0,
                    'max' => 50
                ],
            ])
            ->add('allergy', ChoiceType::class, [
                'label' => 'Allergies',
                'required' => false,
                'mapped' => true,
                'choices' => [
                    'Gluten' => 'Gluten',
                    'Oeufs' => 'Oeufs',
                    'Fruits de mer' => 'Fruits de mer',
                    'Céleri' => 'Céleri',
                    'Sésame' => 'Sésame',
                    'Arachide' => 'Arachide',
                    'Fruits à coque' => 'Fruits à coque',
                    'Moutarde' => 'Moutarde',
                    'Soja' => 'Soja',
                    'Lupin' => 'Lupin',
                    'Lait' => 'Lait',
                    'Mollusques' => 'Mollusques',
                    'Poisson' => 'Poisson',
                    'Sulfites' => 'Sulfites',
                ],
                'multiple' => true,
                'expanded' => true,
            ])            
            ->add('submit', SubmitType::class, [
                'label' => "S'inscrire"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
