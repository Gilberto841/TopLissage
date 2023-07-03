<?php

namespace App\Form;

use App\Model\SearchData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SearchType extends AbstractType
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('query', TextType::class, [
                'attr' => [
                    'placeholder' => $this->translator->trans('Recherche via le code postal') // Placeholder du champ de saisie
                ],
                'empty_data' => '', // Valeur par défaut du champ de saisie
                'required' => true // Champ de saisie requis
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class, // Classe de l'entité utilisée pour les données du formulaire
            'method' => 'GET', // Méthode de soumission du formulaire
            'csrf_protection' => false // Désactiver la protection CSRF pour ce formulaire
        ]);
    }
}
