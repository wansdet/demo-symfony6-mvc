<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\BlogCategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlogCategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id', NumberType::class, [
                'label' => 'ID',
                'html5' => true,
                'attr' => [
                    'min' => 1,
                    'max' => 100,
                ],
            ])
            ->add('name')
            ->add('active')
            ->add('sortOrder', NumberType::class, [
                'label' => 'Sort Order',
                'html5' => true,
                'attr' => [
                    'min' => 1,
                    'max' => 100,
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BlogCategory::class,
        ]);
    }
}
