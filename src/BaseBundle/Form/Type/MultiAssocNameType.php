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


use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;

class MultiAssocNameType extends AbstractType{

    private $entityManager;
    private $request;

    public function __construct(EntityManagerInterface $entityManager,RequestStack $request){
          $this->entityManager = $entityManager;
          $this->request = $request->getCurrentRequest();
    
    }

    
    public function buildForm(FormBuilderInterface $builder, array $options){
      
        
    }
    

    public function configureOptions(OptionsResolver $resolver)
    { 
        $resolver->setDefaults(array(
            'class' =>  BaseNoms::class,
            'name'=>null,
            'key'=>null,
            'value'=>null,
            'compound'=>false,
            'em'=>null,
            'allow_extra_fields'=>true,
        ));
        
    }
    
    public function buildView(FormView $view, FormInterface $form, array $options){
          
          $choicesData = $data=false;
          $data = $form->getData();
          $form = $form->getParent(); 
          
          $view->vars['choices'][$options['key']] = array(
                                        'name'=>$options['name'],
                                        'value'=>$options['value'],
                                        );
         
         

    }
  
}

