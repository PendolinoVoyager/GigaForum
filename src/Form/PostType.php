<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('board')
            ->add('title')
            ->add('text', TextareaType::class)
            ->add('tags', HiddenType::class, ['label' => false])
            ;
        $builder->get('tags')
            ->addModelTransformer(new CallbackTransformer(
        function ($tagsAsArray): string {
            // transform the array to a string
            if (is_null($tagsAsArray)) {
                return '';
            }
            return implode(',', $tagsAsArray);
        },
        function ($tagsAsString): array {
            // transform the string back to an array
            return explode(',', $tagsAsString);
        }));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
