<?php

namespace UsersBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use BaseBundle\Form\Type\BaseNomType;
use BaseBundle\Form\Type\CountryType;
use UsersBundle\Entity\UserAddress;



class UserAddressType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('contactType', BaseNomType::class, ['baseNom' => 'user.address', 'addEmpty'=>false])
            ->add('address1')
            ->add('address1Phonetic')
            ->add('address2')
            ->add('address2Phonetic')
            ->add('place')
            ->add('placePhonetic')
            ->add('postcode')
            ->add('municipality')
            ->add('municipalityPhonetic')
            ->add('country', CountryType::class);
    }



    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => UserAddress::class,
            'label_format' =>'user.form.address.%name%'
        ));
    }
}
