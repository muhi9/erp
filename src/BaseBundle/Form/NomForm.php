<?php
namespace BaseBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use BaseBundle\Form\Type\SwitchType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\PropertyAccess\PropertyPath;
use BaseBundle\Entity\NomType as NomTypeEntity;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
//use BaseBundle\Repository\BaseNomsRepository;

//use BaseBundle\Form\Type\BaseNomType;
//use BaseBundle\Form\Type\TreeChoiceType;


class NomForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
         
            $parent = isset($options['parent'])?$options['parent']:null;
            $data = $builder->getData();        
           
        $builder
            ->add('nameKey')
            ->add('name')
            //->add('parentNameKey1', EntityType::class, ['class'=>NomTypeEntity::class, 'placeholder'=>'','data'=>$parent])
            ->add('parent', EntityType::class, ['class'=>NomTypeEntity::class, 'placeholder'=>'','data'=>$parent])
            ->add('status', SwitchType::class,['switch'=>true])
            ->add('extra', SwitchType::class, ['switch'=>true, 'attr'=>['class'=>'hasExtra' ],'mapped' =>false, 'label'=> 'Extra fields'] );
            if($data->getExtraField()!=null){
                $builder->add('extraField',HiddenType::class, ['data'=>implode(',', $data->getExtraField()),'mapped' =>false])
                ->add('extra',CheckboxType::class, ['attr'=>['class'=>'hasExtra' ],'data'=>true,'mapped' =>false, 'label'=> 'Extra fields'] );
            }
            $builder->add('descr');
            
            $builder->add('save', SubmitType::class);
            /*
            //on submit
            $listener = function (FormEvent $event) {
                $form = $event->getForm();
                $data = $event->getData();
                
                $data["extraField"] = is_array($data["extraField"])?implode(', ',$data["extraField"]):$data["extraField"]; 
                $event->setData($data);
                $form->add('extraField');
                
            };
            $builder->addEventListener(FormEvents::PRE_SUBMIT, $listener);
            */
           

    }

    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
               'parent'=>null,
               'allow_extra_fields'=>true
        ));
    }
}
