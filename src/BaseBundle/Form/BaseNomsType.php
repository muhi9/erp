<?php

namespace BaseBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use BaseBundle\Form\Type\TreeChoiceType;

use BaseBundle\Entity\BaseNoms;
use BaseBundle\Entity\NomType;

use BaseBundle\Form\DataTransformer\BasenomTransformer;


class BaseNomsType extends AbstractType
{
    private $doctrine;
    private $nomTransformer;
    private $nomTypeTransformer;


    public function __construct(BasenomTransformer $nomTransformer) {
        $this->nomTransformer = $nomTransformer;
    }
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

      if (!isset($options['doctrine']) || !$options['doctrine'] instanceof \Doctrine\Common\Persistence\ManagerRegistry)
        throw new \Exception("I need doctrine!");
      $doctrine = $options['doctrine'];
      $this->doctrine = $doctrine;
      if (!isset($options['action']))
        throw new \Exception("I need the action!Set it like: \'action\' => \$this->generateUrl($request->get(\'_route\'));!");


      $baseNom = $parentsTypes = null;
      $treeType = 'select';
      if (isset($options['parent'])) {
        $treeType = 'add';
      }
      // preload parent tree if parentid set
      if (isset($options['parent']) && $options['parent'] instanceof BaseNoms) {
        $baseNom = $options['parent'];
      }
      if (isset($options['parent']) && preg_match('/^[\d]+$/', $options['parent'])) {
        $baseNom = $doctrine->getRepository(BaseNoms::class)->find($options['parent']);
        if (null == $baseNom)
          throw new \Exception("Can't find parentId ".$options['parent'], 400);
      }

      if (isset($options['nomId']) && preg_match('/^[\d]+$/', $options['nomId'])) {
        $baseNom = $doctrine->getRepository(BaseNoms::class)->find($options['nomId']);
      }


      // WARNING: $baseNom might be a parentNom OR nom (based on option passed - parent/nom)
      if ($baseNom) {
        //echo $baseNom->getName() . '  - ' . $baseNom->getId();exit;
        $options['allow_extra_fields'] = true;

        // we have set parentBasenom. We must build a tree and show it.
        $nomType = $baseNom->getType();
        // add extra fields
        if($nomType !== null && $nomType->getExtraField()!=null){

            $ef = $nomType->getExtraField();
            foreach ($ef as $key => $value) {
                $data['extraFields'][$value] = '';
            }
        }
        
        //get all parent keys
        $typeK = $nomType;
        $limit = 0;
        $maxLimit = 150;
        while ($typeK!=null) {
            if ($limit > $maxLimit) break;
            if ($typeK->getId() == $baseNom->getType()->getId()) {
              $r = [
                'id' => $baseNom->getId(),
                'name' => $baseNom->getName(),
                'value' => $baseNom->getName(),
                'type' => $baseNom->getType()->getId(),
              ];
            } else {
              $r = ['type' => $typeK->getId(), 'name'=>$typeK->getName()];
            }
            $parentsTypes[$typeK->getId()] = $r;
            if ($typeK->getParent() != null)
                $typeK = $typeK->getParent();
            else
                $typeK = null;
            ++$limit;
        }

        //print_r($parentsTypes);exit;

        // fill in parents array up to root
        if($baseNom->getParent()!=null) {
            $parent = $baseNom;
            $limit = 0;
            $maxLimit = 150;
            while ($parent!=null) {
              if ($limit > $maxLimit) break;
                ++$limit;
                if ($parent->getId() === null) continue;
                $parentType = $parent->getType()->getId();
                $parentsTypes[$parentType]['id'] = $parent->getId();
                $parentsTypes[$parentType]['name'] = $parent->getType()->getName();
                $parentsTypes[$parentType]['value'] = $parent->getName();
                $parentsTypes[$parentType]['type'] = $parentType;
               
                $parent = $parent->getParent();
            }
            $view['vars']['preloadParents'] = $parentsTypes;
        }


        //echo 'seeee';print_r($parentsTypes);exit;
        if ($parentsTypes) {
            $data['parentsTypes'] = $parentsTypes;
            $data['formProps']['parent'] = $parentsTypes;
            //print_r($parentsTypes);exit;
            //$data['reloadType'] = true;
            //print_r($parentsTypes);exit;
        }
      }



        $type = false;
        if(isset($options['type'])&&!empty($options['type'])) {
          $type = $doctrine->getRepository(NomType::class)->find($options['type']);
          if (!$baseNom && $parentsTypes == null) {
            //we don't have BaseNom && parentsTypes are still empty but we have type.
            // this means we fucked. we have type but don't have parentId/Id of the nom.
            // this means we have to rewind and set the type to closes to root type so user can select
            // TODO. must find a way to build the tree UP AND DOWN the type... :(

          }
        }
        if (!$type) {
          $type = isset($baseNom)?$baseNom->getType():false;
        }
        //echo "T: ".$baseNom->getId();exit;
        //$parent = isset($options['parent'])&&!empty($options['parent'])?$options['parent']:false;

        $data =$builder->getData();

        if ($treeType == 'select') {
          if(!empty($parentsTypes)) {
            // find type parenttyps
            $lparent = null;
            foreach ($parentsTypes as $v) {
              //if (isset($v['type']) && $v['type'] == $type->getParentNameKey1()) {
              if ($type->getParent() !== null && $v['type'] == $type->getParent()->getId()) {
                $lparent = $v;
                //echo 'asd';print_r($parentsTypes);exit;
                break;
              }
              /*
              if (!isset($v['type']) && $kt == $type->getNameKey()) {
                $v['type'] = $kt;
                $v['value'] = '';
                $lparent = $v;
                break;
              }
              */
            }
          }
          //echo $type;exit;
          //echo sizeof($parentsTypes);exit;
            $builder
            //->add('type', EntityType::class, ['class'=>NomType::class, 'placeholder'=>'',
            ->add('type', TreeChoiceType::class, [
              'class'=>NomType::class,
              'placeholder'=>'',
              'choice_label'=>'nameKey',
              'data'=>$type,
              'preloadParents' => $parentsTypes,
              'treeSettings' => $options['treeSettings']
            ]);
//        }
        }
        if ($treeType == 'add') {
          $maxLimit = 100;
          $limit = 0;


          // anonymous recursion function
          $getChildTypes = function($childTypes, $merged = [] ,$depth = 0)use(&$getChildTypes) {
            if ($depth>100) return $merged;
            foreach ($childTypes as $cht) {
              $extra = $cht->getExtraField();
              if ($extra && is_array($extra) && in_array('nullable', $extra)) {
                // this is special case - when field can be nullable, we must select it's sub child so it can be skipped.
                $otherChildTypes = $cht->getChildren()->toArray();
                //print_r($cht);print_r($otherChildTypes);exit;
                if (sizeof($otherChildTypes)>0) {
                  $merged = $getChildTypes($otherChildTypes, $merged, ++$depth);
                }
              }
              if (null !== $cht)
                $merged = array_merge([$cht],$merged);
            }
            return $merged;
          };



          $childTypes = $getChildTypes($type->getChildren()->toArray());
          //print_r($type->getChildren()->toArray());exit;
          //print_r($type->getChildren()->toArray());exit;
          //print_r($childTypes);exit;
          //echo sizeof($childTypes). ' -> '.$type->getId();exit;
          if (sizeof($childTypes)==0) {
            //echo 'this is root el? if not, check for bugs in the nom_type tree!';exit;
            echo 'End of tree. This is last element of the tree. No childs allowed. Set nom type tree. Check for broken nom-type tree!';exit;
            // this is root - special case
            $parentsTypes = null;
            $selectedType = null;
            $lparent = $type;
          } else {
            $selectedType = null;
            if (sizeof($childTypes) == 1)
              $selectedType = $childTypes[0];
            if(!empty($parentsTypes)) {
              // find type parenttyps
              $lparent = null;
              foreach ($parentsTypes as $kt => $v) {
                //if (isset($v['type']) && $v['type'] == $type->getNameKey()) {
                //echo "RRR: ".print_r($v,true) . '?='.$type->getId();
                if ($v['type'] == $type->getId()) {
                  $lparent = $v;
                  break;
                }
              }
            }
            //exit;
            $options['treeSettings']['lastElAsSelect'] = 'true';
          }

          $builder
            ->add('parentType', TreeChoiceType::class, [
              'class'=>NomType::class,
              'placeholder'=>'',
              'choice_label'=>'nameKey',
              'data'=>$type,
              'mapped' => false,
              'disabled' => true,
              'preloadParents' => $parentsTypes,
              'treeSettings' => $options['treeSettings']
            ])
            /*
            ->add('type', TreeChoiceType::class, [
              'class'=>NomType::class,
              'placeholder'=>'',
              'choice_label'=>'nameKey',
              'choices'=> $childTypes,
              //'preloadParents' => $parentsTypes,
              'treeSettings' => $options['treeSettings']
            ]);
            */
            //->add('type', EntityType::class, ['class'=>NomType::class, 'placeholder'=>'',
            ->add('type', EntityType::class, [
              'class'=>NomType::class,
              'placeholder'=>'',
              'choice_label'=>'nameKey',
              'choices'=>$childTypes,
              'data' => $selectedType,
            ])
            ;
        }

        if(!empty($parentsTypes)) {
          //$lparent = end($parentsTypes);
          //print_r($lparent);
          //print_r($parentsTypes);exit;

          if (null === $parentsTypes)
            throw new \Exception("Basenom parent not found", 404);
          //if (!isset($lparent['id']))
          //  throw new \Exception("Basenom parent id not set/found", 404);
          if (isset($lparent['id'])) {
            $builder->add('parent', HiddenType::class, ['data'=>$lparent['id'],'attr'=>['readonly'=>'readonly'],'required'=> false]);
            $builder->get('parent')->addModelTransformer($this->nomTransformer);
          }

          //print_r($lparent);exit;
          if (isset($lparent['name']) && isset($lparent['value']))
            $builder->add('parent_name', null, ['data'=>$lparent['value'] . '('.$lparent['name'].')','attr'=>['disabled'=>'disabled'],'mapped' => false,'required'=> false]);

        }
        $builder->add('status',\BaseBundle\Form\Type\SwitchType::class);
        $builder->add('bnomKey');

/*
        if(!empty($parent)){
            $n=0;
            $last = null;
            foreach ($parent as $key => $value) {
              $n++;
                $builder->add($n, null,[
                //'choice_label'=>$value['name'],
                'data'=> (!isset($value['value'])?'---> BUG (empty value): '.$value['name']:$value['value']),
                //'attr'=>['disabled'=>'disabled'],
                'attr'=>['disabled'=>'disabled'],
                'mapped' => false,
                'required'   => false,
                'empty_data' => (!isset($value['value'])?'---> BUG (empty value): '.$value['name']:$value['value']),
                'label'=>$value['name']
                ]);
                $last = $value;
            }
            //print_r($value);exit;
            $builder->add('parent_id', TextType::class, [
                'data' => $last['id'],
            ]);
            $builder->add('parent_id_type', TextType::class, [
                'data' => $last['type'],
                'mapped' => false,
            ]);
        }
*/
        if(null != $data &&  $data->getId()!=null){
          if (empty($parentsTypes)) {
            $builder->add('name');
          }

        }
        $builder->add('save',SubmitType::class);


        // dynamically set data in form when submitting. this way we set data for unmapped fields (that has been submited)
        // and have setters in it's entity
        // when we add fields with JS this must be done, otherwise data won't be set automatically in entity
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
          $dataForm = $event->getData();
          //$form = $event->getForm();
          $formData = $event->getForm()->getData();
          //echo 'runme:';
          //print_r(($dataForm));exit;
          //print_r(get_class_methods($dataForm));

          foreach ($dataForm as $key => $value) {
            //echo "K: ".$key." $value\n";
            if (property_exists($formData, $key)) {
              //echo "$key exists. set it  to $value";
              $kn = ucfirst($key);
              $pos = strpos($kn,'_');
              if ($pos)
                $kn = substr($kn,0,$pos) . ucfirst(substr($kn,$pos+1));
              $kn = 'set'.$kn;
              if (!method_exists($formData, $kn))
                throw new \Exception('Error setting data for form. '. get_class($formData)." doesn't have method: ".$kn, 1345);
              if ($key == 'parent' && ! $value instanceof \BaseBundle\Entity\BaseNoms) {
                $prn = $this->doctrine->getRepository(BaseNoms::class)->find($value);
                if (null === $prn)
                  throw new \Exception("Error finding parent by id: ".$value, 1442);
                $value = $prn;
              }
              if ($key == 'type' && ! $value instanceof \BaseBundle\Entity\NomType) {
                $prn = $this->doctrine->getRepository(NomType::class)->find($value);
                if (null === $prn)
                  throw new \Exception("Error finding type by key: ".$value, 1443);
                $value = $prn;
              }
              //echo "CALL $kn - > $value\n";
              $formData->$kn($value);
            }
          }

          // checks if the Product object is "new"
          // If no data is passed to the form, the data is "null".
          // This should be considered a new "Product"
          //if (!$dataForm || null === $dataForm->getId()) {
              //$form->add('name', TextType::class);
          //}
        });
    }


    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
               'type'=>false,
               //'parent'=>null, // sets parent
               'allow_extra_fields'=>true,
               'doctrine' => null, // doctrine instance
               'treeSettings' => [ // settings of the tree
                  'showTree' => 'true', // hides the tree in accordeon
                  'disableSelected' => 'false', // if true, all tree nom that are selected are disabled
                  'hideLastEl' => 'false', // hides last tree element - the one edited at the moment.
                  'lastElAsSelect' => 'false', // shows last element as select
                  'lastTreeElement' => null, // define LAST tree element. If key of the basenom = this string js won't add more els.
                ],
               'parent' => null, //sets parent to preload data from - object/id are accepted
               'nomId' => null, //sets nomid to preload data from
               'treeType' => 'select', // define what operation you make - select tree || add tree. if add tree parent_id type/tree is parent and after it shows select type + input for this type.
               // if in select mode parent_id is the final tree menu selection.
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'BaseBundle_basenoms';
    }


}
