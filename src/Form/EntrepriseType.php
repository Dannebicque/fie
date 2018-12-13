<?php

namespace App\Form;

use App\Entity\Diplome;
use App\Entity\Entreprise;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntrepriseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('societe', TextType::class, ['label' => 'Nom de l\'entreprise '])
            ->add('adresse', TextType::class, ['label' => 'Adresse de l\'entreprise '])
            ->add('cp', TextType::class, ['label' => 'Code postal de l\'entreprise '])
            ->add('ville', TextType::class, ['label' => 'Ville de l\'entreprise '])
            ->add('representants', CollectionType::class, [
                'entry_type'    => RepresentantType::class,
                'entry_options' => ['label' => false],
                'allow_add'     => true,
                'prototype'     => true,
                'allow_delete'  => true,
                'by_reference'  => false,
                'label' => 'L\'entreprise sera représentée par (inscrire toutes les personnes présentes): ',
                'attr'          => array(
                    'class' => 'selector-representant',
                ),
            ])
            //todo: au moins l'un des deux premier obligatoire pour avoir le pot
            ->add('presentation_entreprise', CheckboxType::class, [
                'label'    => 'Souhaite présenter l\'entreprise sur un stand',
                'required' => false
            ])
            ->add('jobdating', CheckboxType::class, [
                'label'    => 'Souhaite participer au Job Dating avec des offres de stage',
                'required' => false
            ])
            ->add('potcloture', CheckboxType::class, [
                'label'    => 'Souhaite participer au pôt de cloture du forum',
                'required' => false
            ])
            ->add('paspresent', CheckboxType::class, [
                'label'    => 'Ne souhaite pas participer au forum, mais propose des offres de stage',
                'required' => false
            ])
            ->add('nbchaises', ChoiceType::class, [
                'label'    => 'Nombre de chaises souhaitées (2 par défaut) ',
                'choices' => [1 => 1, 2 => 2, 3 => 3, 4 => 4]
            ])
            ->add('nbtables', ChoiceType::class, [
                'label'    => 'Nombre de tables souhaitées (1 par défaut (100cm x 140cm) ',
                'choices' => [1 => 1, 2 => 2, 3 => 3, 4 => 4]
            ])
            ->add('prise', ChoiceType::class, [
                'label'    => 'Besoin d\'une prise électrique ? ',
                'choices' => ['Oui' => true, 'Non' => false],
                'expanded' => true
            ])
            ->add('remarques', TextareaType::class, ['label' => 'Remarques ou suggestions (contraintes d\'horaires, besoins spécifiques, ...)', 'required' => false])
            ->add('offres', CollectionType::class, [
                'entry_type'    => OffreType::class,
                'entry_options' => ['label' => 'Offre de stage'],
                'allow_add'     => true,
                'prototype'     => true,
                'allow_delete'  => true,
                'by_reference'  => false,
                'attr'          => array(
                    'class' => 'selector-offre',
                ),
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Entreprise::class,
        ]);
    }

    public function getBlockPrefix()
    {
        return null;
    }
}
