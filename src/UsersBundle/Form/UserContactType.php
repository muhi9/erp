<?php

namespace UsersBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use BaseBundle\Form\Type\BaseNomType;
use UsersBundle\Entity\UserContact;



class UserContactType extends AbstractType {
    protected $contact_types = [
        'phone' => 'user.phone',
        'mail' => 'user.mail',
        'url' => 'user.url',
        'soc' => 'user.soc',
        'im' => 'user.im',
        'emergency' => 'user.emergency',
        'call_sing' => 'user.company_address',
    ];



    public function buildView(FormView $view, FormInterface $form, array $options) {
        $view->vars['contact_type'] = $options['contact_type'];
    }



    public function buildForm(FormBuilderInterface $builder, array $options) {
        if (empty($this->contact_types[$options['contact_type']])) {
            throw new \Exception("Invalid contact_type for UserContactType, available options are: ".implode(', ', array_keys($this->contact_types)), 1);
        }
        $builder->add('contactType', BaseNomType::class, [
            'baseNom' => $this->contact_types[$options['contact_type']],
            'addEmpty'=>false
        ]);
        switch ($options['contact_type']) {
            case 'phone':
                $builder->add('info2', null, ['label'=>'Country Code', 'attr'=>['class'=>'mask-regex', 'regex'=>'\s*(\+|00)\s*\d+']]);
                $builder->add('info1', TelType::class, ['label'=>'Number','attr'=>['class'=>'mask-regex', 'regex'=>'^\+?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{3})[-. ]?([0-9]{3})$']]);
                break;
            case 'mail':
                $builder->add('info1', EmailType::class, ['label'=>'E-maill']);
                break;
            case 'url':
                $builder->add('info1', UrlType::class, ['label'=>'URL']);
                break;
            case 'soc':
                $builder->add('info1', null, ['label'=>'Profile']);
                break;
            case 'im':
                $builder->add('info1', null, ['label'=>'Profile']);
                break;
            case 'emergency':
                $builder->add('info1', null, ['label'=>'Person Name']);
                /*$builder->add('info1', TelType::class, ['label'=>'Contact Phone']);
                $builder->add('emergencyContactBnomPhoneType', BaseNomType::class, [
                    'baseNom' => $this->contact_types['phone'],
                    'addEmpty'=>false
                ]);
                $builder->add('info3',EmailType::class, ['label'=>'Contact Email']);
                $builder->add('emergencyContactBnomEmailType', BaseNomType::class, [
                    'baseNom' => $this->contact_types['mail'],
                    'addEmpty'=>false
                ]);*/
                break;
            case 'call_sing':
                $builder->add('info1', null, ['label'=>'Call Sing']);
                break;
            
        }
    }

    public function validate($data, ExecutionContextInterface $context) {
        $fields = [];
        /*if ($data->getPersonalInfo()->getPersonType()->getBnomKey()=='person') {
            if($data->getContactType()->getType()->getNameKey()=='user.emergency'&&empty($data->getInfo3())){
              $fields = ['info3'];
            }
            
        }*/

        if(!empty($fields)){
            foreach ($fields as $field) {
                $tmp = 'get'.ucfirst($field);
                if (!$data->$tmp()) {
                    $context
                        ->buildViolation('users.form.error.'.$field.'_is_blank')
                        ->atPath($field)
                        ->addViolation();
                }
            }    
        }
    }



    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => UserContact::class,
            'label_format' => 'user.form.contact.%name%',
            'contact_type' => null,
            'constraints' => [
                new Callback([$this, 'validate']),
            ],
        ));
    }
}
