<?php

namespace App\Form;

use App\Entity\Figures;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class FiguresType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();
        $author = $user->getUserLastName() . " " . $user->getUserFirstName();

        $builder
            ->add('figureName')
            ->add('figureDescription')
            ->add(
                'figureImg',
                FileType::class,
                [
                    'label' => false,
                    'multiple' => true,
                    'mapped' => false,
                    'required' => false
                ]
            )
            ->add('figureVideo')
            ->add('FigureGroup')
            ->add('figureAuthor', TextType::class, [
                'attr' => [
                    'readonly' => true,
                    'value' => $author
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Figures::class,
        ]);
    }
}
