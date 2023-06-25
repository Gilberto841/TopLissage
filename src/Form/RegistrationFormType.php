<?php

namespace App\Form;

use App\Entity\Professional;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('name', null, [
                'label' => 'Name',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('postalAdress', null, [
                'label' => 'Postal Address',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('siret', null, [
                'label' => 'Siret',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('hairSalon', CheckboxType::class, [
                'required' => false,
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => 'I agree to the terms',
                'attr' => [
                    'class' => 'form-check-input',
                ],
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'label' => 'Password',
                'attr' => [
                    'class' => 'form-control',
                    'autocomplete' => 'new-password',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 4096,
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Professional::class,
        ]);
    }
}
