<?php

namespace BaseBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;


class HeadingType extends AbstractType {
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'attr'=>[
                'class'=>'mask-regex',
                'regex' => '[0-3][0-9][0-9]',
                'placeholder'=>'input.heading.placeholder'
            ],
        ]);

    }

    public function getParent() {
        return TextType::class;
    }

}