<?php

namespace BaseBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use BaseBundle\Form\Type\ACType;
use AirportBundle\Entity\Airport;


class AirportInputType extends AbstractType {

    public function buildView(FormView $view, FormInterface $form, array $options) {
        $view->vars['runway_field'] = $options['runway_field'];
        $view->vars['airport_block_class'] = $options['airport_block_class'] ? $options['airport_block_class'] : ($options['runway_field'] ? 'col-9' : '');
        $view->vars['runway_block_class'] = $options['runway_block_class'] ? $options['runway_block_class'] : 'col-3';
        $view->vars['runway_field_getter'] = 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $view->vars['runway_field'])));
    }



    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'class' => Airport::class,
            'url' => 'airport_autocomplete',
            'url_reload' => 'airport_autocomplete_reload',
            'link' => 'airport_view',
            'link_admin' => 'airport_edit',
            'choice_label' => 'fullName',
            'runway_field' => false,
            'airport_block_class' => '',
            'runway_block_class' => '',
            'filterPredefined' => [
                'get_traffic_type' => false,
                'checkAvailability' => null,
                'eventType' => false, //options: departure, arrival, landings
            ],
        ]);

    }


    
    public function getParent() {
        return ACType::class;
    }

}