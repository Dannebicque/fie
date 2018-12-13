<?php

namespace App\Form;

use App\Entity\Diplome;
use App\Entity\Offre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class OffreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, ['label' => 'Intitulé de l\'offre de stage'])
            ->add('decription', TextareaType::class, ['label' => 'Mission(s) Proposée(s)', 'attr' => ['rows' => 10]])
            ->add('profilrecherche', TextareaType::class, ['label'=>'Profil du candidat recherché', 'required' => false, 'attr' => ['rows' => 10]])
            ->add('documentFile', VichFileType::class, ['label' => 'Joindre un fichier (pdf ou docx ou image)'])
            ->add('diplomes', EntityType::class, ['class' => Diplome::class, 'choice_label' => 'display', 'expanded' => true, 'multiple' => true, 'label' => 'Formation(s) souhaitée(s)'])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Offre::class,
        ]);
    }
}
