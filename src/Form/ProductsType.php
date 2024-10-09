<?php

namespace App\Form;

use App\Entity\Products;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('measure')
            ->add('name')
            ->add('images')
            ->add('price')
            ->add('discount')
            ->add('discount_from')
            ->add('discount_to')
            ->add('meta_description')
            ->add('meta_keywords')
            ->add('sku')
            ->add('url')
            ->add('weight')
            ->add('note')
            ->add('active')
            ->add('quantity')
            ->add('best_seller')
            ->add('show_web')
            ->add('category')
            ->add('manufacture')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Products::class,
        ]);
    }
}
