<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\BlogCategory;
use App\Entity\BlogPost;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlogPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('summary', TextareaType::class, [
                'attr' => [
                    'rows' => 5,
                ],
            ])
            ->add('content', TextareaType::class, [
                'attr' => [
                    'rows' => 15,
                ],
            ])
            ->add('slug')
            ->add('status', TextType::class, [
                'disabled' => true,
            ])
            ->add('blogCategory', EntityType::class, [
                'class' => BlogCategory::class,
                'choice_label' => 'name',
                'placeholder' => 'Choose a category',
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BlogPost::class,
        ]);
    }
}
