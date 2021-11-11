<?php

namespace App\Form;

use App\Entity\Comments;
use App\Entity\USer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentsType extends AbstractType
{


    public function __construct()
    {
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = new USer();
        if ($user) {
            $author = $user->getUserLastName() . " " . $user->getUserFirstName();
            $builder
                ->add('Content', TextareaType::class)
                ->add('Author', TextType::class, [
                    'attr' => [
                        'readonly' => true,
                        'value' => $author
                    ]
                ])
                ->add('Submit', SubmitType::class);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comments::class,
        ]);
    }
}
