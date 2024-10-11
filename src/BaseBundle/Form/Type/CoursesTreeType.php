<?php
namespace BaseBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use BaseBundle\Form\DataTransformer\CoursesTransformer;

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

use CoursesBundle\Entity\CourseElement;
use CoursesBundle\Repository\CourseElementRepository;

use BaseBundle\Entity\BaseNoms;
use BaseBundle\Repository\BaseNomsRepository;

use BaseBundle\Entity\Settings;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class CoursesTreeType extends AbstractType{

    private $entityManager;
    private $constraints = false;
    private $courseElement = array();
    private $transformer;

    public function __construct(EntityManagerInterface $entityManager, CoursesTransformer $transformer){
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
            'class' =>  BaseNoms::class,
            'constraints'=>[],
            'itemsCount'=>10,
            'tree'=>false,
            'compound'=>false,
            'em'=>null,
            'hasChild'=>false,
            'addEmpty'=>true,
            'checkboxlist'=>false,
            'addNew'=>false,
            'expanded'=>true,
            'multiple'=>true,
        ));

    }

    public function buildView(FormView $view, FormInterface $form, array $options){

          $res = [];
          if(!empty($view->vars['value'])){
           //get parent tree

            foreach ($view->vars['value'] as $key => $coursesId) {
              $tmpbaseNoms = $this->entityManager->getRepository(BaseNoms::class)->find($coursesId);
              $res[$tmpbaseNoms->getId()]= $tmpbaseNoms->getId();
              $parent_id = $tmpbaseNoms->getParentId();
              while ($parent_id!=null) {
                $tmpbaseNoms = $this->entityManager->getRepository(BaseNoms::class)->find($parent_id);
                $res[$tmpbaseNoms->getId()]= $tmpbaseNoms->getId();
                $parent_id = $tmpbaseNoms->getParentId();
              }
            }
          }

          $this->constraints['status']=1;
          $orderBy = ['name'=>'ASC'];
          $settings = $this->entityManager->getRepository(Settings::class)->findBy(['type'=>'nomtype']);
          $settings =!empty($settings)?$settings[0]->getSettings():false;
          $baseNomsTree = array();
          if(!empty($this->constraints)){
             $this->courseElement = array();
             //coureses
             //$this->baseNoms = $this->entityManager->getRepository(BaseNoms::class)->findBy($constraints,$orderBy);
             $this->courseElement = $this->entityManager->getRepository(CourseElement::class)->findBy(['status'=>1]);

             foreach ($this->courseElement as $key => $value) {
               $courseKey = $value->getCourseId()->getId();
               $childConstraints =['parent_id'=>$courseKey,'type'=>'course.practice'] ;

               //practice
               $practice = $this->entityManager->getRepository(BaseNoms::class)->findBy($childConstraints,$orderBy);
               if(!empty($practice)){
                $baseNomsTree[$courseKey]['name'] = $value->getCourseId()->getName();
               }

               foreach ($practice as $key => $value){
                 $practiceKey = $value->getId();
                 $baseNomsTree[$courseKey]['practice'][$practiceKey]['name'] = $value->getName();
                 $childConstraints=['parent_id'=>$practiceKey,'type'=>'course.phase'];
                 $phase = $this->entityManager->getRepository(BaseNoms::class)->findBy($childConstraints,$orderBy);
                 foreach ($phase as $key => $value){
                   $phaseKey = $value->getId();
                   $baseNomsTree[$courseKey]['practice'][$practiceKey]['phase'][$phaseKey]['name'] = $value->getName();
                   $childConstraints=['parent_id'=>$phaseKey,'type'=>'course.exercise'];
                   $exercise = $this->entityManager->getRepository(BaseNoms::class)->findBy($childConstraints,$orderBy);
                    foreach ($exercise as $key => $value){
                     $exerciseKey = $value->getId();
                     $baseNomsTree[$courseKey]['practice'][$practiceKey]['phase'][$phaseKey]['exercise'][$exerciseKey]['name'] = $value->getName();

                    }
                 }
               }
            }
          }

          $view->vars['choices'] =$baseNomsTree;// $this->baseNoms;
      //    $view->vars['st'] = count($this->baseNoms)<$options['itemsCount']?false:true;
          $view->vars['addEmpty'] = $options['addEmpty'];
          $view->vars['attr']['data'] = json_encode(['data-table'=>'BaseBundle:BaseNoms','field'=>'name','criteries'=>$this->constraints]);
          $view->vars['hasChild'] = $options['hasChild'];
          $view->vars['checkboxlist'] = $options['checkboxlist'];
          $view->vars['addNew'] = $options['addNew'];
          $view->vars['settings'] =  $settings;
          $view->vars['value'] =$res;
          //$view->vars = array_merge($view->vars,$options);


    }

}

