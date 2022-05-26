<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',EmailType::class,[
                'disabled' => true,
                'label'=> 'Mon adresse Eamil'
            ])

            ->add('firstname',TextType::class,[
                'disabled' => true,
                'label'=> 'mon prénom'
            ])

            ->add('lastname',TextType::class,[
                'disabled' => true,
                'label'=> 'mon nom'
            ])

            ->add('OldPassword',PasswordType::class,[
                'label' => 'mot de passe actuel',
                'attr' => ['placeholder' => 'merci de saisir le mot de passe actuel'],
                'mapped' => false
            ])

            ->add('NewPassword',RepeatedType::class,[
                'type' => PasswordType::class,
                'mapped' => false,
                'invalid_message' => 'Le mot de passe ne correspond pas !',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'nouveau mot de passe', 'attr' => ['placeholder' => 'Merci de saisir votre mot de passe']],
                'second_options' => ['label' => 'Confirmer le nouveau mot de passe', 'attr' => ['placeholder' => 'Merci de confirmer votre nouveau mot de passe']],
            ])

            ->add('submit',SubmitType::class, ['label' => "Valider"])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
