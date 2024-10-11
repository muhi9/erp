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
use Template\Entity\Template;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;
use Symfony\Component\DependencyInjection\Container;

class TemplateVariablesType extends AbstractType{

    private $entityManager;
    private $request;
    private $oldData = [];
    private $template_variables ;

    public function __construct(Container $container){
        
          $this->entityManager = $container->get('doctrine.orm.default_entity_manager');
          $this->request = $container->get('request_stack')->getCurrentRequest();
          $this->template_variables = $container->getParameter('template_variables'); 
    }


    public function buildForm(FormBuilderInterface $builder, array $options){

      $listener = function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();
            $options = $form->getConfig()->getOptions();
            $parrent = $form->getParent();
            $formName =$parrent->getConfig()->getName();
            $requestData = $this->request->request->get($formName);

            $insertData=[];
            $error = false;
            $this->oldData = [];

            if(isset($requestData['var'])&&!empty($requestData['var'])){
                foreach ($requestData['var'] as $key => $data) {
                   
                    if(isset($data['variable'])&&!empty($data['variable'])){
                            $i_data =[$data['variable']=>['required'=>(isset($data['required'])?true:false)]]; 
                            array_push($insertData,$i_data);
                    }else{
                        $this->oldData[$key] = ['variable'=>'','required'=>(isset($data['required'])?true:false)];
                        $parrent->getData()->setVariables(null);
                        $error = new FormError("This field is required");
                        $form->addError($error);   
                    }   
                }
                
                if(!empty($insertData)){
                    $parrent->getData()->setVariables($insertData);
                }else{
                    $parrent->getData()->setVariables(null);
                    $error = new FormError("This field is required");
                    $form->addError($error);
                }    
            }else{
                $parrent->getData()->setVariables(null);
                $error = new FormError("This field is required");
                $form->addError($error);  
            }
        };

        $builder->addEventListener(FormEvents::PRE_SUBMIT, $listener);

    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'class' =>  Template::class,
            'constraints'=>[],
            'measurements'=>null,
            'compound'=>false,
            'addEmpty'=>true,
            'em'=>null,
            'mapped'=>false,
            'allow_extra_fields'=>true,
        ));

    }

    public function buildView(FormView $view, FormInterface $form, array $options){
        $choicesData = $data=false;
        
        $form = $form->getParent();
        $data = $form->getData();
        $view->vars['formName'] = $form->getName();
        if(!empty($data->getVariables())&&is_array($data->getVariables())){
            $view->vars['data'] =$data->getVariables();
            if(!empty($this->oldData)){
                $view->vars['data'] = array_merge($view->vars['data'],$this->oldData);   
            }
        }else{
            $view->vars['data'][]=['variable'=>false,'required'=>false];
        }
         $view->vars['template_variables']=$this->template_variables;
    }
}
