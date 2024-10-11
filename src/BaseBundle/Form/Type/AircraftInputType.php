<?php

namespace BaseBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AircraftBundle\Entity\Aircraft;


class AircraftInputType extends AbstractType {

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'class' => Aircraft::class,
            'url' => 'aircraft_autocomplete',
            'link' => 'aircraft_view',
            'link_admin' => 'aircraft_edit',
            'choice_label' => 'fullName',
            'filterPredefined' => [
                'required_user' => false,
                'required_flightFrom' => false,
                'get_ifr' => false,
                'checkAvailability' => null,
            ],
            
        ]);

    }


    
    public function getParent() {
        return ACType::class;
    }

}