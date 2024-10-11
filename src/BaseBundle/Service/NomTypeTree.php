<?php

namespace BaseBundle\Service;
use Doctrine\ORM\EntityManager;
use BaseBundle\Entity\NomType;

class NomTypeTree {
/**
     * @var EntityManager
     */
        private $entityManager;



    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

   public function createNomTypeTree($flip = false){
        $nomTypes = $this->entityManager->getRepository(NomType::class)->findAll();
        $nomTypesArray = false;
        $tmpTypesArray = false;
        if ($nomTypes!=null) {
            //subs
            foreach ($nomTypes as $key => $value) {
                if($value->getParentNameKey1()!=null){
                    $posC = explode('=>', $value->getParentNameKey());
                    $deep[trim($posC[0])][count($posC)] =$value->getParentNameKey();
                    $tmpTypesArray[trim(end($posC))][trim($value->getId())] = [
                        'p'=>trim($value->getParentNameKey()),
                        'k'=>trim($value->getId()),
                        'pos'=>count($posC)
                    ];
                }
            }


            foreach ($deep as $key => $parents) {
                $nomTypesArray[$key] = $key;
                $treeKey = max(array_keys($parents));
                $parentTree = explode('=>', $parents[$treeKey]);
                foreach ($parentTree as $level => $parent) {
                    if(isset($tmpTypesArray[$parent])){
                        foreach ($tmpTypesArray[$parent] as $key => $nomType) {
                            $ppp = $nomType;
                            while ($ppp) {
                                $nomTypesArray[$ppp['k']] = '|'.str_repeat('-',$ppp['pos']).$ppp['k'];
                                $ppp =isset($tmpTypesArray[$ppp['k']])?array_shift($tmpTypesArray[$ppp['k']]):false;
                            }
                        }
                    }
                }
            }


            //main
            foreach ($nomTypes as $key => $value) {
                if(!$value->getParentNameKey()&&!isset($nomTypesArray[$value->getId()])){
                    $nomTypesArray[$value->getId()] = $value->getId();
                }

            }
        }
        if($flip){
            $nomTypesArray = array_flip($nomTypesArray);
        }
        return $nomTypesArray;
    }

}