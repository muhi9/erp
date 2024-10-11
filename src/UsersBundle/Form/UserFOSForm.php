<?php

namespace UsersBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use BaseBundle\Form\Type\ProfileInputType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use BaseBundle\Form\Type\SwitchType;


class UserFOSForm extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $current_user = $options['current_user'];
        $builder
            ->add('info', ProfileInputType::class, ['disabled' => !in_array('ROLE_ADMIN', $current_user->getRoles())]);
            if(in_array('ROLE_ADMIN', $current_user->getRoles())){                
                $builder
                ->add('enabled', SwitchType::class)
                ->add('roles', ChoiceType::class, [
                    'multiple' => true,
                    'expanded' => false,
                    'choices' => [
                        'users.form.roles.super_admin' => 'ROLE_SUPER_ADMIN',
                        'users.form.roles.admin' => 'ROLE_ADMIN',
                        'users.form.roles.operator' => 'ROLE_OPERATOR',
                        'users.form.roles.client' => 'ROLE_CLIENT',


                    ],
                ]);
            }
            $builder->add('save', SubmitType::class);
    }



    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'label_format' =>'users.form.%name%',
            'allow_extra_fields' => true,
            'current_user'=>null
        ));
    }



    public function getParent() {
        return UserFOSType::class;
    }

}
