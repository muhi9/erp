<?php
namespace BaseBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use BaseBundle\Form\DataTransformer\BaseNomTagsTransformer;

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

class BaseNomTagsType extends AbstractType {

    private $entityManager;
    private $constraints = false;
    private $courseElement = array();
    private $transformer;
    private $opts = [];

    public function __construct(EntityManagerInterface $entityManager, BaseNomTagsTransformer $transformer){
          $this->entityManager = $entityManager;
          $this->transformer = $transformer;

    }


    public function buildForm(FormBuilderInterface $builder, array $options){
      if(!empty($builder->getMapped())){
        //$builder->addViewTransformer($this->transformer);
        $builder->addModelTransformer($this->transformer);
      }

    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'class' =>  BaseNoms::class,
            'constraints'=>[],
            'compound'=>false,
            'em'=>null,
            //'bnomKey' => 'license_fcl'
            'nomType' => 'license.level0',
            'recursive' => true, // recurse to the end of the tree. might generate a lot of elements. MAX limit 500
            'maxItems' => 2500,
            'onlyLastLeafs' => true, // show only last leafs of the tree (with path FCL->LAPL->A->Additional Rating->Night Rating).
            'placeholder' => 'Enter required tags',
        ));

    }

    public function buildView(FormView $view, FormInterface $form, array $options) {
      $this->opts = $options;
      $res = [];
      if (isset($options['bnomKey'])) {
        $rootNoms = $this->entityManager->getRepository(BaseNoms::class)->findBy(['bnomKey' => $options['bnomKey'], 'status' => 1]);
      }
      if (isset($options['nomType'])) {
        $rootNoms = $this->entityManager->getRepository(BaseNoms::class)->findBy(['type' => $options['nomType'], 'status' => 1]);
      }
      $res = $this->buildDataOptions($rootNoms);
     
      $view->vars['dataTags'] = $res;
      $view->vars['placeholder'] = $options['placeholder'];
      $view->vars['maxItems'] = $options['maxItems'];
    }

    private $res = [];
    private $itemsCount = 0;

    private function buildDataOptions($noms, $path = '', $depth = 0) {
      $this->res = [];
      if ($depth>500) {
        throw new \Exception("so deep!", 909090);

      }
      foreach ($noms as $nom) {

        $r = [
          'id' => $nom->getId(),
          'value' => ($this->opts['recursive']===true?$nom->getTreeName():$nom->getName()),
          //'value' => $nom->getName(),
          'title' => 'not set',//$path . $nom->getName(),
        ];

        // we have reached max items specified
        if ($this->itemsCount > $this->opts['maxItems']) return $this->res;

        if (sizeof($nom->getChildren()) < 1 && true == $this->opts['onlyLastLeafs']) {
          $r['title'] = $nom->getTreeName();
          $this->res[] = $r;
          $this->itemsCount++;
        } else {
          if (true != $this->opts['onlyLastLeafs']) {
            $r['title'] = $nom->getTreeName();
            $this->res[] = $r;
          }
        }
        if (true === $this->opts['recursive'] && sizeof($nom->getChildren())>0) {
          $this->buildDataOptions($nom->getChildren(), $path, ++$depth);
        }
      }
      return $this->res;
    }

}

