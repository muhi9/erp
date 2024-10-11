<?php

namespace BaseBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AirportBundle\Entity\AirportRunway;


class AirportRunwayInputType extends AbstractType {

    public function buildView(FormView $view, FormInterface $form, array $options) {
    	if (empty($options['airport_field'])) {
    		throw new \Exception("Field \"airport_field\" is not set.", 1);
    	}
    	$field = $form->getParent()->get($options['airport_field']); // throw exception
        $view->vars['airport_field'] = $options['airport_field'];
    	$view->vars['choices'] = $field->getData() ? $field->getData()->getRunways()->getValues() : [];
    }



    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'class' => AirportRunway::class,
            'airport_field' => false,
        ]);

    }


    
    public function getParent() {
        return EntityType::class;
    }

}