<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', null,[
                'label' => false,
                'attr' =>['placeholder' => 'E-mail',]]
            )
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                 'label' => false,
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password',
                'placeholder' => 'Mot de passe',],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('prenom', null,[
                'label' => false,
                'attr' =>['placeholder' => 'Prénom',]])
            ->add('nom', null,[
                'label' => false,
                'attr' =>['placeholder' => 'Nom',]])
            ->add('tel', null,[
                'label' => false,
                'attr' =>['placeholder' => 'Numéro de téléphone',]])
            ->add('adresse', null,[
                'label' => false,
                'attr' =>['placeholder' => 'Adresse',]])
            ->add('ville', null,[
                'label' => false,
                'attr' =>['placeholder' => 'Ville',]])
            ->add('cp', null,[
                'label' => false,
                'attr' =>['placeholder' => 'Code Postal',]])
            ->add('pays', null,[
                'label' => false,
                'attr' =>['placeholder' => 'Pays de résidence',]])
            ;
            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
