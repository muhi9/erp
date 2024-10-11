<?php

namespace UsersBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UsersBundle\Entity\UserBankAccount;



class UserBankAccountType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('bank')
            ->add('iban')
            ->add('bic')
            ->add('swift');
    }



    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => UserBankAccount::class,
            'label_format' =>'user.form.bank.%name%'
        ));
    }
}
