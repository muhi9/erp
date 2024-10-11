<?php

namespace BaseBundle\Service;
use Doctrine\ORM\EntityManager;
use BaseBundle\Entity\BaseNoms;
use BaseBundle\Entity\NomType;

class BaseNomeTree {
/**
     * @var EntityManager
     */
        private $entityManager;
        private $treeArray;
        private $last;
        private $lastLevel;
        private $fo = [];


    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

   public function createBaseNomeTree(BaseNoms $basenom) {
        $level = 0;
        $basenomId = $basenom->getId();
        $childs = $basenom->getChildren()->toArray(); //$this->entityManager->getRepository(BaseNoms::class)->findBy(['parent_id'=>$basenomId]);
        if(!empty($childs) && sizeof($childs)>0){
            foreach ($childs as $key => $value) {
                $tmp=[];
                $tmp[$level] = $value->getName();
                //echo 'recurse: '.print_r($tmp,true)."<hr>\n\n";
                $this->recursiveTree($value,$tmp,$level+1);
            }

        }else{
           $this->treeArray[$basenom->getid()] = $basenom->getName();

        }
        //echo "\n<hr>";
    return $this->treeArray;
    }

    private function recursiveTree(BaseNoms $basenom, &$tmp, $level){
        if($level>20) {
            throw new Exception("Going too deep in the rabbit hole", 42);
        }
        //print_r($tmp);
        //$childs = $this->entityManager->getRepository(BaseNoms::class)->findBy(['parent_id'=>$basenom->getId()]);
        $childs = $basenom->getChildren()->toArray();
        if(!empty($childs) && sizeof($childs)>0){
            //echo sizeof($childs).'->';
           foreach ($childs as $key => $value) {
                if($level<$this->lastLevel){
                    $parLevel = $level-1;
                    if(!isset($tmp[$parLevel]))
                        break;
                    $tmp = array($parLevel=>$tmp[$parLevel]);
                }
                $tmp[$level]=$value->getName();
                $this->last=$value->getId();
                $this->lastLevel = $level;
                $this->recursiveTree($value,$tmp,$level+1);

            }
        } else {
            $this->treeArray[$this->last] = implode('=>',$tmp);
        }
    }


    public function setFo($fo){
        $this->fo=$fo;
    }

    public function getFo(){
        return $this->fo;
    }

    public function baseNomsTree(BaseNoms $basenom,$reverse = true){
        $parentsTypes=[];
        if($basenom!=null){
            $nomType = $basenom->getType()->getId();
            $parentsTypes[$nomType]['value'] = $basenom->getName();
            $parentsTypes[$nomType]['name'] = $nomType;
            $parentsTypes[$nomType]['id'] = $basenom->getId();
            if(!empty($basenom->getExtra())){
                foreach ($basenom->getExtra() as $key => $value) {
                    $parentsTypes[$nomType]['extra'][$value->getBaseKey()] = $value->getBaseValue();
                }
            }
            $parent = $basenom->getParent();
            if($parent!=null){
                $maxDepth = 200;
                $depth = 0;
                while ($parent!=null) {
                    if ($depth>$maxDepth) {
                        throw new \Exception("Going too deep in rabbit whole! ".$depth . ' ' .$basenom->getId(), 42);
                    }
                    $bparentsType = $parent->getType()->getId();
                    if ($bparentsType == 'module.root') break;
                    //$parentName = $this->entityManager->getRepository(NomType::class)->find($parent->getType());
                    $parentsTypes[$bparentsType]['value'] = $parent->getName();
                    $parentsTypes[$bparentsType]['name'] = $parent->getType()->getName(); //$parentName->getName();
                    $parentsTypes[$bparentsType]['id'] = $parent->getId();
                    if(!empty($parent->getExtra())){
                        foreach ($parent->getExtra() as $key => $value) {
                            $parentsTypes[$bparentsType]['extra'][$value->getBaseKey()] = $value->getBaseValue();
                        }
                    }
                    $parent = $parent->getParent();
                    $depth++;
                }
            }
        }

        return  ($reverse?array_reverse($parentsTypes):$parentsTypes);

    }

}
