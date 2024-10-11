<?php

namespace BaseBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;


class FrequencyType extends AbstractType {
    
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'attr'=>[
                'class'=>'mask-regex ',
                //'regex' => '[0-9]{1,3}\.|\,[0-9]{1,10}',
                'regex' => '[0-9]{1,4}([\.|\,][0-9]{1,10})?',
                'placeholder' => 'input.frequency.placeholder'
            ],
        ]);

    }

    public function getParent() {
        return TextType::class;
    }

}