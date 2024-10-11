<?php
namespace UsersBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use BaseBundle\Form\Type\BaseNomType;
use BaseBundle\Form\Type\CountryType;
use BaseBundle\Form\Type\ProfileInputType;
use BaseBundle\Form\Type\UserInputType;
use BaseBundle\Form\Type\ACType;
use BaseBundle\Form\Type\UnitsAltitudeType;
use BaseBundle\Form\Type\UnitsCenterOfGravityType;
use BaseBundle\Form\Type\UnitsDistanceType;
use BaseBundle\Form\Type\UnitsFlowType;
use BaseBundle\Form\Type\UnitsVolumeType;
use BaseBundle\Form\Type\UnitsLatitudeType;
use BaseBundle\Form\Type\UnitsLengthType;
use BaseBundle\Form\Type\UnitsPressureType;
use BaseBundle\Form\Type\UnitsTemperatureType;
use BaseBundle\Form\Type\UnitsTimeType;
use BaseBundle\Form\Type\UnitsVelocityType;
use BaseBundle\Form\Type\UnitsVerticalSpeedType;
use BaseBundle\Form\Type\UnitsVisibilityType;
use BaseBundle\Form\Type\UnitsWeightType;
use BaseBundle\Form\Type\UnitsWindSpeedType;
use BaseBundle\Form\Type\SwitchType;

use UsersBundle\Form\UserContactType;
use UsersBundle\Form\EmergencyType;
use UsersBundle\Form\UserAddressType;
use UsersBundle\Form\UserIDDocumentType;
use UsersBundle\Form\UserBankAccountType;
use UsersBundle\Form\UserCourseType;
use UsersBundle\Form\UserFOSType;
use UsersBundle\Entity\User;
use UsersBundle\Entity\UserIDDocument;
use BaseBundle\Entity\BaseNoms;
use BaseBundle\Repository\BaseNomsRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use BaseBundle\Form\Type\UserMessageProviderType;
use Psr\Container\ContainerInterface;
use FileBundle\Form\Type\FileInputCollectionType;




class UserForm extends AbstractType {

    private $container;
    private $personSubTypes = [];
    private $formValidatorGroups = ['Default'];
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }


    public function buildForm(FormBuilderInterface $builder, array $options) {
       // $builder->add('personType', BaseNomType::class, ['baseNom'=>'user.type','addEmpty'=>false]);
        $current_user = $options['current_user'];
       // if (!$builder->getData()->getId()) {
            $this->formValidatorGroups = ['Default','Registration'];
            $builder
                ->add('personType', BaseNomType::class, ['baseNom'=>'user.type','addEmpty'=>false]);
        //}

//////////////////////////////////////////////////////////////////////////////////////////////////// PERSON

 
            $builder
                ->add('credit', NumberType::class, ['disabled'=>true, 'scale'=>2])
                ->add('delayed_payment', NumberType::class)
                ->add('firstName')
                ->add('middleName')
                ->add('lastName')
                ->add('nationality', CountryType::class)
                ->add('languages', ChoiceType::class, [
                    'choices' => [
                        'En' => 0,
                        'Bg' => 1,
                        'De' => 2,
                    ],
                    'multiple' => true,
                    'expanded' => false,
                ]);
                if(in_array('ROLE_ADMIN', $current_user->getRoles())){
                    $builder->add('personSubTypePerson', EntityType::class, [
                        'class' => BaseNoms::class,
                        'choice_label' => 'name',
                        'query_builder' => function (BaseNomsRepository $er) {
                            return $er->createQueryBuilder('u')
                                ->where('u.type = :type')
                                ->join('u.parent','p')
                                ->andWhere('p.bnomKey = :bnomkey')
                                ->setParameters(['type'=>'user.sub_type','bnomkey'=>'person']);
                        },
                        'expanded' => true,
                        'multiple' => true,
                        'mapped' => false,
                        'attr' => [
                            'class' => 'border-0',
                            'isCheckboxList' => true
                        ],
                        'data'=>$builder->getData()->getPersonSubType()
                    ]);
                }
            $builder->add('company');

//////////////////////////////////////////////////////////////////////////////////////////////////// LEGAL PERSON
        
            $builder
                ->add('companyName')
                ->add('companyType', BaseNomType::class, ['baseNom' => 'user.company_type', 'addEmpty'=>false])
                
              //  ->add('trademark',null,['mapped'=>false,'data'=>$builder->getData()->getNickname()])
                //->add('nickname')
                ->add('companyID')
                ->add('companyVAT')
                ->add('companyPerson');
                /*->add('personSubTypeCompany', EntityType::class, [
                'class' => BaseNoms::class,
                'choice_label' => 'name',
                'query_builder' => function (BaseNomsRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.type = :type')
                        ->join('u.parent','p')
                        ->andWhere('p.bnomKey = :bnomkey')
                        ->setParameters(['type'=>'user.sub_type','bnomkey'=>'company']);
                },
                'expanded' => true,
                'multiple' => true,
                'mapped' => false,
                'attr' => [
                    'class' => 'border-0',
                    'isCheckboxList' => true
                ],
                'data'=>$builder->getData()->getPersonSubType()
            ]);*/
        


        $nickname_attr = [];
        if ($builder->getData()->getId() && $builder->getData()->getPersonType()->getBnomKey()=='company') {
            $nickname_attr = ['label'=>'users.form.trademark'];
        }
        $nickname_attr['disabled'] = (!in_array('ROLE_ADMIN', $current_user->getRoles()) && !in_array('ROLE_OPERATOR', $current_user->getRoles()));
        $builder->add('nickname', null, $nickname_attr);

        $builder->add('parentOrganisation', ProfileInputType::class,['filterPredefined'=>['personType'=>'company']]);


        if ($builder->getData()->getId() && in_array('ROLE_ADMIN', $current_user->getRoles())) {
            $builder->add('disabled', SwitchType::class, ['switch'=>true]);
        }


//////////////////////////////////////////////////////////////////////////////////////////////////// LOGIN
/*        $builder
            ->add('user', CollectionType::class, [
                'entry_type' => ::class,
                'entry_options' => [
                    'label' => false,
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' => true,
                //'by_reference' => false,
            ]);
*/
        if (!$builder->getData()->getId()) {
            $builder
                ->add('user', UserFOSType::class, [
                    'compound' => true,
                    'by_reference' => false,
                ]);
        }else {
            $builder->add('user', UserInputType::class, [
                'filterPredefined' => [
                    'withProfile' => false,
                ],'disabled' => (!in_array('ROLE_ADMIN', $current_user->getRoles()) && !in_array('ROLE_OPERATOR', $current_user->getRoles())),
            ]);
        }
        $builder->add('images', CollectionType::class, [
                'entry_type' => FileInputCollectionType::class,
                'entry_options' => [
                    'baseNom1' => ['enabled'=>true, 'nomKey'=>'user.images'],
                    'gridList' => true,
                    'mime'=>'image',
                    'resize_image' => function($formData) {
                        $result = ['max_width'=>false, 'max_height'=>false, 'keep_ratio'=>true];
                        if (!empty($formData['bnomType1'])) {
                            $bnom = $this->container->get('doctrine.orm.entity_manager')->getRepository(BaseNoms::class)->find($formData['bnomType1']);
                            $result = array_merge($result, $bnom->getExtraArray());
                        }
                        return $result;
                    },
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' => true,
                'by_reference' => false,
            ]);
        /*
        $builder->add('avatar', FileInputType::class, [
            'mime'=>'image',
            'resize_image' => [
                'max_width'=>150,
                'max_height'=>150
            ],
        ]);
        */
//////////////////////////////////////////////////////////////////////////////////////////////////// CONTACTS
        $builder
            ->add('phone', CollectionType::class, [
                'entry_type' => UserContactType::class,
                'entry_options' => [
                    'label' => false,
                    'contact_type' => 'phone'
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' => true,
                'by_reference' => false,
            ])
            ->add('mail', CollectionType::class, [
                'entry_type' => UserContactType::class,
                'entry_options' => [
                    'label' => false,
                    'contact_type' => 'mail'
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' => true,
                'by_reference' => false,
            ])
            ->add('url', CollectionType::class, [
                'entry_type' => UserContactType::class,
                'entry_options' => [
                    'label' => false,
                    'contact_type' => 'url'
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' => true,
                'by_reference' => false,
            ]);
        if (!$builder->getData()->getId() || $builder->getData()->getPersonType()->getBnomKey()=='person') {

            $builder->add('soc', CollectionType::class, [
                'entry_type' => UserContactType::class,
                'entry_options' => [
                    'label' => false,
                    'contact_type' => 'soc'
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' => true,
                'by_reference' => false,
            ]);
            
        }

        $builder->add('addresse', CollectionType::class, [
            'entry_type' => UserAddressType::class,
            'entry_options' => ['label' => false],
            'allow_add' => true,
            'allow_delete' => true,
            'delete_empty' => true,
            'by_reference' => false,
        ]);
        if (!$builder->getData()->getId() || $builder->getData()->getPersonType()->getBnomKey()=='company') {

            $builder->add('call_sing', CollectionType::class, [
                'entry_type' => UserContactType::class,
                'entry_options' => [
                    'label' => false,
                    'contact_type' => 'call_sing'
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' => true,
                'by_reference' => false,
            ]);
        }



//////////////////////////////////////////////////////////////////////////////////////////////////// BANK ACCOUNT
        $builder
            ->add('bank', CollectionType::class, [
                'entry_type' => UserBankAccountType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' => true,
                'by_reference' => false,
            ])
            ->add('message_provider', UserMessageProviderType::class)
            ->add('save', SubmitType::class, ['attr'=>['next'=>true]]);

        $submit = function (FormEvent $event) use ($builder, $options) {

            $current_user = $options['current_user'];
            $data = $event->getData();
            $form = $event->getForm();

            if($data->getPersonType()->getBnomKey()=='person'){
                if(in_array('ROLE_ADMIN', $current_user->getRoles())){
                    $data->setPersonSubType($form->get('personSubTypePerson')->getData());
                }
            }else{
                $data->setPersonSubType($form->get('personSubTypeCompany')->getData());
            }






        };
        $builder->addEventListener(FormEvents::POST_SUBMIT, $submit);
        $preSubmit = function (FormEvent $event) use ($builder, $options) {
            $current_user = $options['current_user'];
            $data = $event->getData();
            $form = $event->getForm();

            if((isset($data['personSubTypeCompany'])&&!empty($data['personSubTypeCompany']))
                ||(isset($data['personSubTypePerson'])&&!empty($data['personSubTypePerson']))) {

                $personSubType = isset($data['personSubTypeCompany'])?$data['personSubTypeCompany']:$data['personSubTypePerson'];

                foreach ($personSubType as $key => $typeId) {
                    $bnom = $this->container->get('doctrine.orm.entity_manager')->getRepository(BaseNoms::class)->find($typeId);
                    $this->personSubTypes[] = $bnom->getBnomKey();
                }
            }

            if(isset($data['user']['plainPassword']) && empty($data['user']['plainPassword']['first']) && empty($data['user']['plainPassword']['second'])) {
                $data['user']['plainPassword']['first'] = 'pleasechangethispassword';
                $data['user']['plainPassword']['second'] = 'pleasechangethispassword';
                $event->setData($data);
            }
        };
        $builder->addEventListener(FormEvents::PRE_SUBMIT, $preSubmit);
    }



    public function validate($data, ExecutionContextInterface $context) {

        if ($data->getPersonType()->getBnomKey()=='person') {
                $fields = ['firstName', 'lastName', 'nickname'];
                if(count($this->personSubTypes) == 1 && in_array('ROLE_PASSENGER', $this->personSubTypes)) {
                    unset($fields[3]); //unset nickname
                    unset($fields[4]); //unset dateOfEnrolment
                }
        } else {
            $fields = ['companyName'];
        }
        foreach ($fields as $field) {
            $tmp = 'get'.ucfirst($field);
            if (!$data->$tmp()) {
                $context
                    ->buildViolation('users.form.error.'.$field.'_is_blank')
                    ->atPath($field)
                    ->addViolation();
            }
        }

        if(empty($data->getId())) {
            //if mail is empty set data->nickname@example.com
            if(empty($data->getUser()->getEmail())) {

                if($data->getPersonType()->getBnomKey()=='person') {
                    $temp_mail = $data->getNickname()!=null?$data->getNickname():$data->getFirstName().$data->getLastName().$data->getId();
                }else{
                    $temp_mail = $data->getNickname()!=null?$data->getNickname():$data->getCompanyName();
                }

                $data->getUser()->setEmail(strtolower($temp_mail).'@examplemail.com');
                $data->getUser()->setEmailCanonical(strtolower($temp_mail).'@examplemail.com');
            }
        }

        if(!empty($data->getDisabled())) {
            $data->getUser()->setEnabled(false);
        }

        //set ROLES
        if($data->getUser()!==null && $data->getPersonSubType() !== null) {
            $roles = [];

            $temp_roles = !empty($data->getId())?$data->getPersonSubType()->getValues():$data->getPersonSubType();
            foreach ($temp_roles as $role){
                $roles[] = $role->getBnomKey();
            }

            $roles = in_array('ROLE_SUPER_ADMIN', $data->getUser()->getRoles()) ? array_merge($roles, ['ROLE_SUPER_ADMIN']):$roles;
            $data->getUser()->setRoles($roles);
        }

    }



    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'label_format' =>'users.form.%name%',
            'allow_extra_fields' => true,
            'constraints' => [
                new Callback([$this, 'validate']),
            ],
            'validation_groups' => $this->formValidatorGroups,
            'current_user'=>null,
        )) ;
    }


}



