<?php
namespace BaseBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Form\CallbackTransformer;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Query\Parameter;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\ChoiceList\ORMQueryBuilderLoader;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\OptionsResolver\Options;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityManager;

use BaseBundle\Entity\BaseNoms;
use BaseBundle\Form\Type\BaseNomType;

use BaseBundle\Repository\BaseNomsRepository;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;

class BaseNomMeasurementType extends AbstractType{

    private $entityManager;
    private $request;

    public function __construct(EntityManagerInterface $entityManager,RequestStack $request){
          $this->entityManager = $entityManager;
          $this->request = $request->getCurrentRequest();
    
    }

    
    public function buildForm(FormBuilderInterface $builder, array $options){
   
      $listener = function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();
            $options = $form->getConfig()->getOptions();
            $measurements = isset($options['measurements']['name'])?$options['measurements']['name']:false;
            $required = isset($options['required'])?$options['required']:false;
            
            $parrent = $form->getParent();
            $formName =$parrent->getConfig()->getName();
            $requestData = $this->request->request->get($formName);
            if((empty($data)||empty($requestData[$measurements]))&&!empty($required)){
               $error = new FormError("This value or measurements should not be blank ");
               $form->addError($error);
            }else{
              $measurementsData=$this->entityManager->getRepository(BaseNoms::class)->find($requestData[$measurements]); 
              $setter = 'set'.ucfirst($measurements);
              $parrent->getData()->$setter($measurementsData);
             
              //if($parrent->isValid()){
              //  $parrent->add($measurements);  
              //}
                
            }
              
            
  
      
        };

        $builder->addEventListener(FormEvents::PRE_SUBMIT, $listener);
        
        $preSetData = function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();
            $options = $form->getConfig()->getOptions();
            $measurements = isset($options['measurements']['name'])?$options['measurements']['name']:false;
           
            if(empty($measurements)){
                $dateError = new FormError("must add measurements name");
                $form->addError($dateError);
            }
           

        };
        $builder->addEventListener(FormEvents::PRE_SET_DATA, $preSetData);
        
    }
    

    public function configureOptions(OptionsResolver $resolver)
    { 
        $resolver->setDefaults(array(
            'class' =>  BaseNoms::class,
            'constraints'=>[],
            'measurements'=>null,
            'compound'=>false,
            'addEmpty'=>true,
            'em'=>null,
            'allow_extra_fields'=>true,
        ));
        
    }
    
    public function buildView(FormView $view, FormInterface $form, array $options){
          
          $choicesData = $data=false;
          $data = $form->getData();
          $form = $form->getParent();
          
          
          $getter = 'get'.ucfirst($options['measurements']['name']);
          $constraints = false;
          if($form->getData()->$getter()!= null){
            $choicesData = $form->getData()->$getter();
          }
          
          $view->vars['formName'] = $form->getName();
          
           $view->vars['measurementId']=$options['measurements']['name'];
           if(isset($options['measurements'])&&!empty($options['measurements'])){
            $constraints = !empty($options['measurements']['type'])?array('type'=>$options['measurements']['type']):false;
            $constraints['status']=1;
            $orderBy = ['name'=>'ASC'];
            $baseNoms = $this->entityManager->getRepository(BaseNoms::class)->findBy($constraints,$orderBy);
           }
          
          $res = [];
          $view->vars['choices'] = $baseNoms;
          $view->vars['data'] = $data;
          $view->vars['choicesData'] = $choicesData;
          $view->vars['addEmpty'] = $options['addEmpty']; 
         

    }
  
}

