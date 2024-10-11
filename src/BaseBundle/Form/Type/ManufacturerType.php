<?php
namespace BaseBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use BaseBundle\Form\DataTransformer\BasenomTreeTransformer;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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

use Psr\Container\ContainerInterface;


use Doctrine\ORM\EntityManagerInterface;
use BaseBundle\Entity\NomType;
use BaseBundle\Entity\BaseNoms;
use BaseBundle\Entity\Settings;
use BaseBundle\Repository\BaseNomsRepository;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ManufacturerType extends AbstractType{
    private $entityManager;
    private $constraints = false;
    private $baseNoms = array();
    private $transformer;
    private $container = false;
    private $lastElement = false;
    private $parents = array();
    private $fileds = array();

    public function __construct(EntityManagerInterface $entityManager,BasenomTreeTransformer $transformer,ContainerInterface $container){
          $this->entityManager = $entityManager;
          $this->transformer = $transformer;
          $this->container = $container;
    }

    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder->addViewTransformer($this->transformer);
        $listener = function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();
        };

        $builder->addEventListener(FormEvents::PRE_SUBMIT, $listener);

        $preSetData = function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();

            if($data!=null){
               $bnom = (gettype($data)!='object'?$form->getParent()->getData():$data);

              $this->parents = $this->container->get('base_nome_tree')->baseNomsTree($bnom);
            }

        };

        $builder->addEventListener(FormEvents::PRE_SET_DATA, $preSetData);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'class' =>  BaseNoms::class,
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
            'last_element'=>false,
            'parents'=>$this->parents,
            'settings'=>[],
        ));

    }

    public function buildView(FormView $view, FormInterface $form, array $options){
          $options['parents'] = $this->parents;

         if(isset($options['settings']['parent'])&&!empty($options['settings']['parent'])){
             $this->baseNoms = array();
             $this->baseNoms = $this->entityManager->getRepository(BaseNoms::class)->findBy(['parent_id'=>$options['settings']['parent']->getId()]);
             $child = $this->container->get('base_repo')->nomTypeList(['criteries'=>['slike'=>['parentNameKey'=>$options['settings']['parent']->getType()->getId()]]]);
             $child = !empty($child)?$child[0]->getId():0;
            $options['parent']= $options['settings']['parent'];
            $options['child'] = $child;
            $parentsSelect = $options['settings']['parent']->getType()->getId();

         }

         if(isset($options['settings']['nomtype'])&&!empty($options['settings']['nomtype'])){
            $view->vars['nomtypes'] =  $this->container->get('nom_type_tree')->createNomTypeTree(true);
         }

         $options['settings']['full_name'] =$view->vars['full_name'];
         $view->vars = array_merge($view->vars,$options);
    }

}

