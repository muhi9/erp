<?php

namespace BaseBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class FilePreviewType extends AbstractType {

    public function buildView(FormView $view, FormInterface $form, array $options) {
        $view->vars['url'] = $options['url'];
    }



    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'url' => false
        ]);
    }


    
    public function getParent() {
        return TextType::class;
    }

}