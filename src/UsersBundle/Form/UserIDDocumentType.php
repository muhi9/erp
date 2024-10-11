<?php

namespace UsersBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use BaseBundle\Form\Type\BaseNomType;
use BaseBundle\Form\Type\CountryType;
use UsersBundle\Entity\UserIDDocument;
use FileBundle\Form\Type\FileInputType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;


use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class UserIDDocumentType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
            ->add('documentType', BaseNomType::class, ['baseNom' => 'user.id_document', 'addEmpty'=>false])
            ->add('number')
            ->add('personalID')
            ->add('authority')
            ->add('authority_phonetic')
            ->add('country', CountryType::class)
            ->add('issued', DateType::class)
            ->add('expire', DateType::class)
            ->add('file', FileInputType::class)//, ['baseNom1' => 'user.id_document', 'baseNom1Label' => 'Test label'])
            ;

    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => UserIDDocument::class,
            'label_format' =>'user.form.id_document.%name%'
        ));
    }
}
