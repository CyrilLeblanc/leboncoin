<?php

namespace App\Form;

use App\Dto\Research;
use App\Entity\SubCategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ResearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $defaultMinCost = 0;
        $defaultMaxCost = 99999;
        $builder
            ->add('page', HiddenType::class, [
                'empty_data' => 1,
            ])
            ->add('category', EntityType::class, [
                'class' => SubCategory::class,
                'choice_label' => 'name',
                'label' => 'Category'
            ])
            ->add('request', ChoiceType::class, [
                'label' => 'Request for ',
                'choices' => [
                    'demand' => 'Demand',
                    'offer' => 'Offer',
                ],
            ])
            ->add('query', SearchType::class, [
                'label' => 'Search',
                'required' => false,
                'empty_data' => ''
            ])
            ->add('minCost', NumberType::class, [
                'label' => 'Min cost',
                'required' => false,
                'html5' => true,
                'attr' => [
                    'placeholder' => $defaultMinCost,
                    'min' => $defaultMinCost,
                    'empty_data' => $defaultMinCost,
                ],
            ])
            ->add('maxCost', NumberType::class, [
                'label' => 'Max cost',
                'required' => false,
                'html5' => true,
                'attr' => [
                    'placeholder' => $defaultMaxCost,
                    'min' => $defaultMinCost,
                    'empty_data' => $defaultMaxCost,
                ],
            ])
            ->add('postalCode', NumberType::class, [
                'label' => 'Postal Code',
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Search'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Research::class,
        ]);
    }
}
