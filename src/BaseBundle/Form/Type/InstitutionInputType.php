<?php

namespace BaseBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use InstitutionBundle\Entity\Institution;


class InstitutionInputType extends AbstractType {

    public function buildView(FormView $view, FormInterface $form, array $options) {
      
    }


    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'class' => Institution::class,
            'url' => 'institution_autocomplete',
            'allow_create' => false,
            'link' => 'institution_view',
            'link_admin' => 'institution_edit',
            'choice_label' => 'name',
            'filterPredefined' => [
            ]
        ]);

    }



    public function getParent() {
        return ACType::class;
    }

}
