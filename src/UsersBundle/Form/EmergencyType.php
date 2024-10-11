<?php

namespace UsersBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Psr\Container\ContainerInterface;
use BaseBundle\Entity\BaseNoms;
use UsersBundle\Entity\UserPersonalInfo;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;    

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TelType;

use UsersBundle\Form\UserContactType;

class EmergencyType extends AbstractType {

    private $container = false;
    public function __construct(ContainerInterface $container){
              $this->container = $container;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->addViewTransformer(new \Symfony\Component\Form\CallbackTransformer(
            function ($data) { // for html
                return $data;
            },
            function ($data){
                $data['emergency_contact']['contactType'] = ['id'=>$data['emergency_contact']['contactType']->getId(),'name'=>$data['emergency_contact']['contactType']->getName()]; 
                if(isset($data['phone'])&&!empty($data['phone'])){
                    foreach ($data['phone'] as $key => $value) {
                        $data['phone'][$key]['contactType'] = ['id'=>$value['contactType']->getId(),'name'=>$value['contactType']->getName()]; 
                    }
                }
                if(isset($data['mail'])&&!empty($data['mail'])){
                    foreach ($data['mail'] as $key => $value) {
                        $data['mail'][$key]['contactType'] = ['id'=>$value['contactType']->getId(),'name'=>$value['contactType']->getName()]; 
                    }
                }
                
                return $data; 
            }
        ));
     
        $builder
            ->add('emergency_contact', UserContactType::class,[ 'contact_type' => 'emergency','data_class'=>null])
            ->add('phone', CollectionType::class, [
                    'entry_type' => UserContactType::class,
                    'entry_options' => [
                        'label' => false,
                        'contact_type' => 'phone',
                        'data_class'=>null,
                    ],
                    'allow_add' => true,
                    'allow_delete' => true,
                    'delete_empty' => true,
                    'by_reference' => false,
                    'prototype_name'=>'__emr_phone__',
                ])
            ->add('mail', CollectionType::class, [
                    'entry_type' => UserContactType::class,
                    'entry_options' => [
                        'label' => false,
                        'contact_type' => 'mail',
                        'data_class'=>null,
                    ],
                    'allow_add' => true,
                    'allow_delete' => true,
                    'delete_empty' => true,
                    'by_reference' => false,
                    'prototype_name'=>'__emr_mail__',
                ]);
    }
   


    
    public function buildView(FormView $view, FormInterface $form, array $options) {
    }


    public function validate($data, ExecutionContextInterface $context) {
        $fields = [];
        if(empty($data['emergency_contact']['info1'])){
            $fields[] = ['field'=>'info1','msg_key'=>'emergency_contact'];
        }
        if(isset($data['phone'])){
            foreach ($data['phone'] as $key => $phone) {
                if(!$phone['info1']){
                    $fields[]=['field'=>'info1','msg_key'=>'phone'];    
                }
                       
            }
        }
        if(isset($data['mail'])){
            foreach ($data['mail'] as $key => $mail) {
                if(!$mail['info1']){
                    $fields[]=['field'=>'info1','msg_key'=>'mail'];    
                }
                       
            }
        }
        if(!empty($fields)){
            foreach ($fields as $field) {
                    $context
                        ->buildViolation('users.form.error.emergency.'.$field['msg_key'].'_is_blank')
                        ->atPath($field['field'])
                        ->addViolation();
            }    
        }    
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
         
            'allow_extra_fields' => true,
            'constraints' => [
                new Callback([$this, 'validate']),
            ],
            'class' =>  UserPersonalInfo::class,
            
         
        )) ;
        
    }
    

}