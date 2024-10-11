<?php

namespace BaseBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class CountryType extends AbstractType {
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'class' => 'BaseBundle:Country',
            'choice_label' => 'name',
            'required'=>false
        ]);

    }


    
    public function getParent() {
        return EntityType::class;
    }

}
