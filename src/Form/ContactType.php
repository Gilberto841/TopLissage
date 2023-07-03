<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Ajoute les différents champs du formulaire
        $builder
        ->add('name', TextType::class,[
            'label' => 'votre nom/prénom',
            'constraints' => [
                // Contrainte : le champ ne doit pas être vide
                new NotBlank(),
                // Contrainte : la longueur du champ doit être comprise entre 5 et 180 caractères
                new Length([
                    'min' => 5,
                    'max' => 180
                ])
            ]
        ])
        ->add('email', EmailType::class, [
            'label' => 'votre email'
        ])
        ->add('phone', TelType::class,[
            'label' => 'tel'
        ])
        ->add('postalAddress', TextType::class,[
            'label' => 'Adress Postal'
        ])
        ->add('codePostal', TextType::class,[
            'label' => 'Code Postal'
        ])
        ->add('objet', TextType::class,[
            'label' => 'Objet'
        ])
        ->add('message', TextareaType::class)
        ->add('submit', SubmitType::class, [
            'label' => 'Envoyer',
            'attr' => [
                // Attribut : classe CSS pour le bouton
                'class' => 'd-block mx-auto my-3 col-6 btn btn-primary'
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        // Configuration des options du formulaire
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
