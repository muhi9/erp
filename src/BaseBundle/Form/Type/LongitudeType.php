<?php

namespace BaseBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;


class LongitudeType extends AbstractType {
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new \Symfony\Component\Form\CallbackTransformer(
            function ($data) { // for html
                return $data;
            },
            function ($data){
                return strtoupper($data);
            }
        ));
    }


    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'attr'=>[
                'class'=>'mask-regex touppercase',
                'regex' => '(W|w|E|e)([0|1][0-7][0-9])\ ([0-5][0-9])\ ([0-5][0-9])\.([0-9][0-9])',
                'placeholder' => 'input.longitude.placeholder'
            ],
        ]);

    }

    public function getParent() {
        return TextType::class;
    }

}