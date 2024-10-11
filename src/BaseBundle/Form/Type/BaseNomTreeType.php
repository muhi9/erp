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

class BaseNomTreeType extends AbstractType{
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

        // DO NOT FORGET TO ADD THIS LINE IN MAIN FORM!!! (PRE_SUBMIT will not bind here, but only in main form)

        /*
        // WARNING: this line is VERY important. It created PRE_SUBMIT listener so all data is set to entity!!!!
        // DO NOT MISS IT OUT!!!
        $builder->addEventSubscriber(new \BaseBundle\Form\EventListener\BNomTreeTypeSaveFields($this->entityManager,$this->request));
        */



    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' =>  BaseNoms::class,
            //AUTO SET BELOW in default setter method.
            //'dataType' => 'parent', // parent||nomTypeKey - presets widget behaviour based on two types
            //BASE options. ONE of these two MUST be set to work.
            // based on the option set widget behaves diferently.
            'parent' => null,
            'nomTypeKey' => null,
            // DO NOT REMOVE! Validation will fail!
            'constraints'=>[],
            'compound'=>false,
            //
            /*
              NOT NEEDED old options?
            'baseNom'=>false,
            'hasChild'=>false,
            'hasParent'=>false,
            'class' =>  BaseNoms::class,
            'itemsCount'=>10,
            'em'=>null,
            'checkboxlist'=>true,
            */
            'addEmpty'=>true,
            'addNew'=>false,
            'last_element'=>false,
            'parents'=>$this->parents,
            'BaseNomLoad' => [], // preloaded noms. if defined the defined nomtypes will be loaded and will select automatically values in it. This happens recursevly while tree finish. This way you can preselect already saved tree.

            'settings' => [
              'showExtra'=>true,
              'nomtype'=>null,
              // WARNING! WARNING! IMPORTANT!!!
              // When you use these in code
              // COPY THE WHOLE array in the code!!!
              // !!!!!!!!! stupid opitonsresolver won't do settings recursuvely!!!!
              'tree' => [
                  //'showTree'=>true , //  show nomenclature tree accordeon
                  'show'=>true , //  show nomenclature tree accordeon
                  'disableSelected' => false, //set select disabled if defined and found in BaseNomLoad
                  //TODO: implement
                  'hideEmptyNullable' => false, // hides empty elements (no options) that can be nullable in tree. same as notAddEmptyNullable but will add the field and hide it.
                  'notAddEmptyNullable' => false, // prefetches data for given type. do not add field at all if data is empty and field is nullable - same as hideEmptyNullable, but don't make dom at all!
                  'selectFirstOnOne' => true, // selects first option in only one in dropdown
                  'showExpandTopLabel' => true, // show/hide expand accordion tree label
                  'expandTopLabel' => 'Nomenclature tree', // top accodrion tree expand/hide label
                  'focusSelectedOnInit' => false, // do not focus select on init+select - makes page to jump around
                ],
            ],

            /*
              Example usage in CourseController - edit
              Using Basic Repository method like this will fill the array:
        $baseNomLoad = ['course.revision' => [ 'id'=>$course->getRevision()->getId()]];
        $parents = $this->getDoctrine()->getRepository(CourseElement::class)->findParentTree($course->getSubElement());
        $baseNomLoad = array_merge($baseNomLoad,$parents);

              Format for element in BaseNomLoad array is this:
              BaseNomLoad['course.subElement'] = [
                'id'=>$revision->getId(), // basenom ID,
                'name'=>$revision->getNomTypeId(), // name of the nom type
                'value' => $revision->getName(),
                'type' => $revision->getType()->getId(),
              ]
              {"id":622,"value":"Carbon","type":"course.subElement","name":"Course sub element"};
            */
        ])
        // read more: https://symfony.com/doc/current/components/options_resolver.html
        ->setDefault('dataType', function(Options $opts){
          if (null !== $opts['parent'])
            return 'parent';
          if (null !== $opts['nomTypeKey'])
            return 'nomTypeKey';
          return 'you must set either parent OR nomTypeKey variable!'; //fail to set value. should throw exception
        })
        ->setRequired('dataType')
        ->setAllowedValues('dataType', ['parent','nomTypeKey'])

        //->setRequired('parent')
        //->setRequired('nomTypeKey')
        ;

    }

    public function buildView(FormView $view, FormInterface $form, array $options) {
      //print_r($options);exit;
      if (isset($options['parents']))
        $options['parents'] = $this->parents;


      if('parent' == $options['dataType']) {//isset($options['settings']['parent'])&&!empty($options['settings']['parent'])) {
        $this->baseNoms = array();
        if (is_object($options['parent'])) {
          //$this->baseNoms = $this->entityManager->getRepository(BaseNoms::class)->findBy(['parent_id'=>$options['settings']['parent']->getId()], ['order' => 'ASC']);
          $this->baseNoms[] = $options['parent'];//$this->entityManager->getRepository(BaseNoms::class)->findBy(['id'=>$options['settings']['parent']->getId()], ['order' => 'ASC']);
        } elseif (is_string($options['parent'])) {
          $this->baseNoms = $this->entityManager->getRepository(BaseNoms::class)->findBy(['type'=>$options['parent']], ['order' => 'ASC']);
        } else {
          $this->baseNoms = $this->entityManager->getRepository(BaseNoms::class)->findBy($options['parent'], ['order' => 'ASC']);
        }
        if (empty($this->baseNoms)) {
          throw new \Exception("Invalid BaseNomTreeType[settings][parent]. Trying to find: ".$options['parent'], 1);
        } else {

          $parent = $this->baseNoms[0];
          $child = $this->container->get('base_repo')->nomTypeList(['criteries'=>['slike'=>['parentNameKey'=>$parent->getType()->getId()]]]);
          $child = !empty($child)?$child[0]->getId():0;
          $options['parent']= $parent;
          $options['child'] = $child;
          //$parentsSelect = $parent->getType()->getId();
        }
      }


      // WARNING: this is a special mode of the widget.
      // if set, the widget will look for this key in basenom and bsaed on that leaf menu will be built
      // this makes it possible to load a tree by bnomkey vlaue.
      // for eg. when in courses, you can't just load CPL as parent and all sub els as tree, but u can only load courses.
      // with this option you can load CPL as parent and all below as a menues. (used in Airplane Manufacturers)
      if('nomTypeKey' == $options['dataType']) {
      //isset($options['settings']['nomTypeKey'])&&!empty($options['settings']['nomTypeKey'])) {
        //set type to be visible in js params
        $this->baseNoms = array();
        if (is_object($options['nomTypeKey'])) {
          //$this->baseNoms = $this->entityManager->getRepository(BaseNoms::class)->findBy(['parent_id'=>$options['settings']['parent']->getId()], ['order' => 'ASC']);
          $this->baseNoms[] = $options['nomTypeKey'];//$this->entityManager->getRepository(BaseNoms::class)->findBy(['id'=>$options['settings']['parent']->getId()], ['order' => 'ASC']);
        } elseif (is_string($options['nomTypeKey'])) {
          $this->baseNoms = $this->entityManager->getRepository(BaseNoms::class)->findBy(['bnomKey'=>$options['nomTypeKey']], ['order' => 'ASC']);
        }
        if (empty($this->baseNoms)) {
          throw new \Exception("Invalid BaseNomTreeType[settings][nomTypeKey]. Trying to find bnom_key: ".$options['nomTypeKey'], 1);
        } else {

          $parent = $this->baseNoms[0];
          //echo $parent->getName();exit;
          //echo $parent->getChildren()[0]->getType()->getId();exit;
          $child = $this->container->get('base_repo')->nomTypeList(['criteries'=>['slike'=>['nameKey'=>$parent->getChildren()[0]->getType()->getId()]]]);
          //echo 'd: '. $child[0]->getId() . '; type: '.$child[0]->getId();exit;
          //echo sizeof($child);exit;
          //foreach($child as $ch) { echo "D: ".$ch->getId()."\n<br>"; }exit;
          $child = !empty($child)?$child[0]->getId():0;
          //print_r($parent->getType()->getId());exit;
          $options['parent']= $parent;
          $options['child'] = $child;
          $options['childId'] = $parent->getId();
          //$parentsSelect = $parent->getType()->getId();
          $this->parents = $this->container->get('base_nome_tree')->baseNomsTree($parent->getChildren()[0]);
          $options['parents'] = $this->parents;
          //print_r($options['parents']);exit;
          //echo $parentsSelect;exit;
        }
      }

      // ARE WE USING THIS??? maybe for name=entity case??
      if(isset($options['settings']['nomtype']) && !empty($options['settings']['nomtype'])) {
        $view->vars['nomtypes'] = $this->container->get('nom_type_tree')->createNomTypeTree(true);
      }

      $options['settings']['full_name'] = $view->vars['full_name'];
      $options['settings']['dataType'] = $options['dataType'];

      $view->vars = array_merge($view->vars,$options);
    }
}

