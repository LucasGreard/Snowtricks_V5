<?php

namespace App\Form;

use App\Entity\Figures;
use App\Entity\USer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class FiguresType extends AbstractType
{

    public function __construct()
    {
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('figureName', TextType::class, [
                'label' => "Nom de la figure"
            ])
            ->add('figureDescription', TextareaType::class, [
                'label' => "Description complète"
            ])
            ->add(
                'figureImg',
                FileType::class,
                [
                    'label' => false,
                    'multiple' => true,
                    'mapped' => false,
                    'required' => false,
                    'help' => "Vous pouvez ajouter plusieurs fichiers",
                ]
            )
            ->add('figureVideo', TextType::class, [
                'label' => false,
                'required' => false,
                'help' => "Vous pouvez ajouter plusieurs vidéos séparer par une virgule (,)"
            ])
            ->add('FigureGroup', ChoiceType::class, [
                'choices' => [
                    'Grab' => "Grab",
                    'Rotations' => "Rotations",
                    'Flips' => "Flips",
                    'Rotations Désaxées' => "Rotations Désaxées",
                    'Slides' => "Slides",
                    'One foot tricks' => "One foot tricks",
                    'Old School' => "Old School"
                ],
                'label' => "Group :"
            ])
            ->add('figureAuthor', TextType::class, [
                'attr' => [
                    'readonly' => true,
                ],
                'label' => "Author :"
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Figures::class,
        ]);
    }
}
