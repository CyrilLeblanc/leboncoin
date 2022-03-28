<?php

namespace App\Form;

use App\Dto\Post;
use App\Entity\SubCategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true
            ])
            ->add('category', EntityType::class, [
                'required' => true,
                'class' => SubCategory::class,
                'choice_label' => 'name'
            ])
            ->add('price', NumberType::class, [
                'required' => true,
                'html5' => true,
                'attr' => [
                    'min' => 0,
                    'step' => 0.01
                ]
            ])
            ->add('detail', TextareaType::class, [
                'required' => false,
                'attr' => [
                    'rows' => 10,
                    'cols' => 50
                ]
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
