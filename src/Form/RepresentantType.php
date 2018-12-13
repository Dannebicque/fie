<?php

namespace App\Form;

use App\Entity\Entreprise;
use App\Entity\Representant;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RepresentantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('civilite', ChoiceType::class, ['label' => 'Civilité ', 'choices' => ['M.' => 'M.', 'Mme' => 'Mme']])
            ->add('nom', TextType::class, ['label' => 'Nom '])
            ->add('prenom', TextType::class, ['label' => 'Prénom '])
            ->add('email', TextType::class, ['label' => 'Email '])
            ->add('telephone', TextType::class, ['label' => 'Téléphone ', 'required' => false, 'help' => 'Uniquement pour vous contacter en cas de besoin. Ne sera pas diffusé.'])
            ->add('fonction', TextType::class, ['label' => 'Fonction dans l\'enteprise ', 'required' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Representant::class,
        ]);
    }
}
