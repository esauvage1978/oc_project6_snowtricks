<?php

namespace App\Form\Profil;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class PasswordChangeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('plainPasswordOld', TextType::class,
                [
                    'label' => 'Mot de passe actuel',
                ])
            ->add('plainPassword', TextType::class, [
                'label' => 'Nouveau mot de passe',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de saisir le mot de passe',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Le mot de passe doit avoir au minimum {{ limit }} caractères.',
                        'max' => 4096,
                    ]), ], ])
            ->add('plainPasswordConfirmation', TextType::class,
                [
                    'label' => 'Confirmation',
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
        ]);
    }
}
