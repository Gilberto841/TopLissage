<?php

namespace App\Form;

use App\Model\SearchData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SearchType extends AbstractType
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Ajout des champs 'query' et 'submit' au formulaire
        $builder
            ->add('query', TextType::class, [
                'attr' => [
                    'placeholder' => $this->translator->trans('Recherche via le code postal'), // Placeholder du champ de saisie
                    'class' => 'form-control mb-10 m-3 text-center' // Classes Bootstrap pour le champ de saisie
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Ce champ ne peut pas être vide.']), // Contrainte : champ requis
                    new Type(['type' => 'string', 'message' => 'Ce champ doit être une chaîne de caractères.']), // Contrainte : type de données
                    new Regex([
                        'pattern' => '/^\d+$/', // Expression régulière : chiffres uniquement
                        'message' => 'Ce champ doit contenir uniquement des chiffres.'
                    ])
                ],
                'empty_data' => '', // Valeur par défaut du champ de saisie
                'required' => false // Champ de saisie non requis
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Recherche', // Libellé du bouton de soumission
                'attr' => [
                    'class' => 'btn bg-primary m-3 px-3s text-bg-primary' // Classes Bootstrap pour le bouton de soumission
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        // Configuration des options du formulaire
        $resolver->setDefaults([
            'data_class' => SearchData::class, // Classe de l'entité utilisée pour les données du formulaire
            'method' => 'GET', // Méthode de soumission du formulaire
            'csrf_protection' => false // Désactiver la protection CSRF pour ce formulaire
        ]);
    }
}