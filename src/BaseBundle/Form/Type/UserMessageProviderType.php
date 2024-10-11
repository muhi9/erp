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
use UsersBundle\Entity\UserPersonalInfo;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;

class UserMessageProviderType extends AbstractType{

    private $entityManager;
    private $request;
    private $msgs_type = [];
    private $form_error = [];
    private $old_data = [];


    public function __construct(EntityManagerInterface $entityManager,RequestStack $request){
          $this->entityManager = $entityManager;
          $this->request = $request->getCurrentRequest();
          $this->msgs_type = $this->entityManager->getRepository(BaseNoms::class)->findBy(['type'=>'message.type','status'=>1]);
    }


    public function buildForm(FormBuilderInterface $builder, array $options){

      $listener = function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();
            $options = $form->getConfig()->getOptions();
            $parrent = $form->getParent();
            $formName =$parrent->getConfig()->getName();
            $requestData = $this->request->request->get($formName);
            $msgs_type_extra = [];
            
            if(!empty($this->msgs_type)){
                foreach ($this->msgs_type as $key => $value) {
                    $msgs_type_extra[$value->getId()] = $value->getExtraArray();
                }
            }
            
            
            $insertData=[];
            if(isset($requestData['msgs_provider'])&&!empty($requestData['msgs_provider'])){
                foreach ($requestData['msgs_provider'] as $typeId => $data) {

                    $tmp_silent = [];
                    $n = 0;
                    if(!empty($data['contact'])){
                        if(isset($msgs_type_extra[$typeId]['regex'])&&!empty($msgs_type_extra[$typeId]['regex'])){
                            if(!preg_match('/'.$msgs_type_extra[$typeId]['regex'].'/', $data['contact'],$m)){
                                $this->form_error[$typeId]['contact'] = 'users.form.not.valid.value';
                            }else{
                                $this->old_data[$typeId] = $data;
                            }  
                        }   
                        
                        foreach ($data['silent'] as $key => $value) {
                            if(!empty($value['from'])&&!empty($value['to'])){
                                $tmp_silent[$n] = $value;
                                $n++;
                            }
                        }

                        $i_data =['contact'=>$data['contact'],
                                'active'=>(isset($data['active'])?true:false),
                                'silent'=>$tmp_silent];
                        $insertData[$typeId] = $i_data;
                        //$tmp = json_encode($i_data);
                        //array_push($insertData,$tmp);
                    }  
                }
                //$insertData=$i_data;
                
                if(!empty($insertData)&&empty($this->form_error)){
                    $parrent->getData()->setMessageProvider($insertData);
                }else{
                    $parrent->getData()->setMessageProvider(null);
                    $error = new FormError("This field is required");
                    $form->addError($error);
                }    
            }else{
                $parrent->getData()->setMessageProvider(null);
                $error = new FormError("At least one notification field must be set");
                $form->addError($error);  
            }
        };

        $builder->addEventListener(FormEvents::PRE_SUBMIT, $listener);

    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'class' =>  UserPersonalInfo::class,
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
        $view->vars['msgs_type'] =[];
        
        if(!empty($this->msgs_type)){
            $view->vars['msgs_type'] = $this->msgs_type;
        }


        $choicesData = $data=false;
        $form = $form->getParent();
        $data = $form->getData();
        $view->vars['formName'] = $form->getName();
        $view->vars['default_mail'] = !empty($data) && $data->getUser()!=null?$data->getUser()->getEmail():'';
        $view->vars['form_error'] = $this->form_error;
        
        if(!empty($data->getMessageProvider())){
            $view->vars['data'] =[];
            /*foreach ($data->getMessageProvider() as $key => $value) {
              $tmp = json_decode($value,true);
              $typeId = array_keys($tmp)[0];
              $view->vars['data'][$typeId] =$tmp[$typeId];
            }*/
            $view->vars['data'] = $data->getMessageProvider();
            if(!empty($this->old_data)){
                //$view->vars['data'] = array_merge($view->vars['data'],$this->old_data);
            }

            
        }else{
            $view->vars['data'][]=['contact'=>false,'active'=>false,'silent'=>[['from'=>false,'to'=>false]]];
        }

        
       
        
    }
}
