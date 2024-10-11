<?php

namespace BaseBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use BaseBundle\Entity\EduInstitution;


class EduInstitutionInputType extends AbstractType {

    public function buildView(FormView $view, FormInterface $form, array $options) {
      
    }


    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'class' => EduInstitution::class,
            'url' => 'edu_institution_autocomplete',
            'allow_create' => false,
            'link' => false,//'edu_institution_view',
            'link_admin' => false,//'edu_institution_edit',
            'choice_label' => 'name',
            'filterPredefined' => [
            ]
        ]);

    }



    public function getParent() {
        return ACType::class;
    }

}
