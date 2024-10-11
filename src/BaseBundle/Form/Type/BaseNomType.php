<?php
namespace BaseBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use BaseBundle\Form\DataTransformer\BasenomTransformer;

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
use BaseBundle\Entity\Settings;
use BaseBundle\Repository\BaseNomsRepository;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
    example:
    include
    use BaseBundle\Form\Type\BaseNomType;
    parameters:

    baseNom => type of basenom from basenom table 'ac.type' (aircraft type)
    hasChild => if this field is linked dropdown name of child (category)
    addNew => add option --new-- when select you can add new value to basenom current type example(type=>'ac.type', value=>'new value')
    hasParent => parent of current field use for parent when create new value example(parrent=>123,type=>'ac.type', value=>'new value')
    parent_id => criteria for BaseNoms which load in dropdown example (parent_id=>'123456')

    $builder
        ->add('type', BaseNomType::class,['baseNom' => 'ac.type','hasChild'=>'category'])
        ->add('category', BaseNomType::class,['baseNom' => 'ac.category','hasChild'=>'crew','hasParent'=>'type'])

    for linked dropdown include in twig
    {% javascripts '@base_nom_load_child'%}
             <script src="{{ asset_url }}" type="text/javascript"></script>
    {% endjavascripts %}
*/

class BaseNomType extends AbstractType{

    private $entityManager;
    private $constraints = false;
    private $baseNoms = array();
    private $transformer;

    public function __construct(EntityManagerInterface $entityManager,BasenomTransformer $transformer){
          $this->entityManager = $entityManager;
          $this->transformer = $transformer;

    }


    public function buildForm(FormBuilderInterface $builder, array $options){


      if(!empty($builder->getMapped())){
        $builder->addViewTransformer($this->transformer);
      }


    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'class' => BaseNoms::class,
            'constraints'=>[],
            'itemsCount'=>10,
            'baseNom'=>false,
            'compound'=>false,
            'em'=>null,
            'hasChild'=>false,
            'hasParent'=>false,
            'addEmpty'=>true,
            'checkboxlist'=>false,
            'addNew'=>false,
            'parent_id'=>null,
            'attr' => ['class' => 'form-control'],
            'placeholder_is_selectable' => false,
            'placeholder_select_text' => '--Select--',
            'filter_options' => false,
        ));

    }

    public function buildView(FormView $view, FormInterface $form, array $options){

        $parentForm = $form->getParent();
        $parentData = false;
        if(!empty($options['hasParent'])){
            if(is_object($parentForm->getData())){
                $getter = 'get'.ucfirst($options['hasParent']);
                if($parentForm->getData()->$getter()!=null){
                    $parentData = $parentForm->getData()->$getter()->getId();
                }
            }

            if(is_array($parentForm->getData())){
                $parentFormData = $parentForm->getData();
                if(isset($parentFormData[$options['hasParent']])){
                    $parentData = $parentFormData[$options['hasParent']];
                }
            }
        }

        $this->constraints = !empty($options['baseNom'])?array('type'=>$options['baseNom']):false;
        $this->constraints['status']=1;
        if(!empty($parentData)){
            $this->constraints['parent_id']=$parentData;
        }

        if(!empty($options['parent_id'])){
            $this->constraints['parent_id']=$options['parent_id'];
        }

        $orderBy = ['order'=>'ASC'];
       
        $settings =false;

        if(!empty($this->constraints)){
            $this->baseNoms = array();
            $this->baseNoms = $this->entityManager->getRepository(BaseNoms::class)->findBy($this->constraints,$orderBy);
        }
        if (is_callable($options['filter_options'])) {
            $this->baseNoms = array_filter($this->baseNoms, $options['filter_options']);
        }


        $view->vars['choices'] = $this->baseNoms;
        $view->vars['st'] = count($this->baseNoms)<$options['itemsCount']?false:true;
        $view->vars['addEmpty'] = $options['addEmpty'];
        $view->vars['placeholder_is_selectable'] = $options['placeholder_is_selectable'] ? '' : 'disabled';

        $class = [];
        if(isset($options['hasChild'])&&!empty($options['hasChild'])){
            $class[] =' hasChild';
        }

        if(isset($options['addNew'])&&!empty($options['addNew'])){
            $class[] =' addNewNom';
        }

        if (isset($view->vars['attr']['class'])) {
            $view->vars['attr']['class'] .= implode(' ', $class);
        }else{
            $view->vars['attr'] = ['class'=>implode(' ', $class)];
        }
        if (isset($options['attr']['placeholder'])) {
            $view->vars['attr']['placeholder'] = $options['attr']['placeholder'];
        }

          $view->vars['attr']['type'] = $this->constraints['type'];
          $view->vars['hasChild'] = $options['hasChild'];

          $view->vars['hasParent'] = $options['hasParent'];
          $view->vars['checkboxlist'] = $options['checkboxlist'];
          $view->vars['addNew'] = $options['addNew'];
          $view->vars['settings'] =  $settings;

          //$view->vars = array_merge($view->vars,$options);
        $view->vars['placeholder_select_text'] = $options['placeholder_select_text'];
    }

}

