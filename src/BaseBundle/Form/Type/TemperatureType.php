<?php

namespace BaseBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;




class TemperatureType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
    }


    
    public function buildView(FormView $view, FormInterface $form, array $options) {
        $view->vars['default_unit'] = $options['system'] ? 'C' : '';
    }



    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'units'=>[
                'C' => 'input.units.temperature.celsius'
            ],
            'placeholder'=>'input.units.temperature.placeholder',
        ]);

    }



    public function getParent() {
        return UnitsType::class;
    }
}