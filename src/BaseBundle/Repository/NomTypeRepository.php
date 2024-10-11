<?php

namespace BaseBundle\Repository;

use BaseBundle\Entity\NomType;

class NomTypeRepository extends  \Doctrine\ORM\EntityRepository
{
    use \BaseBundle\Repository\BaseRepository;

    public function getParentNameKey($key) {
        if (null === $key) return null;
        $limit = 100;
        $parent = $this->find($key);
        $keys = [$parent->getId()];
        //$parent = $this->find($obj->getId());
        while ($parent->getParentNameKey1() != null) {
            $parent = $this->find($parent->getParentNameKey1());
            $keys[] = $parent->getId();
        }
        return join('=>',array_reverse($keys));
    }


    /**
      * Retrieves ALL nomTypes, order them by nameKey then return assoc array [ac.type]=>'Aircraft type',...]
    */
    public function getNames() {
        $nomTypes = $this->findBy([], ['nameKey'=>'asc']);
        $noms = [];
        foreach($nomTypes as $nom) {
            $noms[$nom->getNameKey()] = $nom->getName();
        }
        return $noms;
    }


    public function getTree($startId=null, $opened = [], $select = [], $maxDeph = 2, $depth = 0) {
        if (null!==$startId && !preg_match('/^[a-zA-Z0-9\.-_]+$/', $startId)) $startId=null;
        if (!isset($select['dotSeparator'])) $dotSeparator = '!_!';
        else
            $dotSeparator = $select['dotSeparator'];
        $noms = $this->findBy(['parentNameKey1' => $startId], ['nameKey'=>'asc',]);
        $data = [];
        $selectedParent=false;
        $selectedType=false;
        if (isset($select['parent']) && preg_match('/^[a-zA-Z0-9\.-_]+$/', $select['parent']))
            $selectedParent = $select['parent'];
        if (isset($select['type']))
            $selectedType = $select['type'];


        foreach($noms as $nom) {
            $striked = (bool)(!$nom->getStatus());
            $r = [
                'id' => str_replace('.', $dotSeparator, $nom->getId()),
                'text' => ($striked?'<strike>':null) . $nom->getName() . '('.$nom->getId().')' . ($striked?'</strike>':null),// <span><a href="https://www.yahoo.com" class="btn btn-sm btn-clean btn-icon btn-icon-md addChildNom"  data-parent-id="'.$nom->getId().'"> <i class="la la-plus-circle"></i></a></span>',
                'data' => [
                    'type' => $nom->getId(),
                    'parent_id'=>(null!==$nom->getParent()?$nom->getParent()->getId():null),
                    'state'=>['opened'=>true],
                ],
                'children' => null,
            ];
            //if (is_array($opened) && (in_array($nom->getParentId(), $opened) || in_array($nom->getId(), $opened)))
            if (is_array($opened) && in_array($nom->getId(), $opened)) // || (!in_array($nom->getId(), $opened) && in_array($nom->getParentId(), $opened)))
                $r['state']['opened'] = true;
            if ($selectedParent !== false && $nom->getId() == $selectedParent)
                $r['state']['selected'] = true;
            //if ($selectedParent == false && $nom->getType()->getId() == $selectedType)
            //    $r['state']['selected'] = true;
            if ($this->count(['parentNameKey1' => $nom->getId()]) > 0) {
                // load maxdepth into initial tree. if deeper - show there's a child
                if ($maxDeph > $depth) {
                    //$data[] = $r;
                    //continue;
                    $r['children'] = $this->getTree($nom->getId(), $opened, $select, $maxDeph, ++$depth);
                } else {
                    $r['children'] = true;
                }
            }
            $data[] = $r;
        }
        return $data;
    }


    public function getParentTreeIds($id, $data = [], $rdata = [], $depth = 0) {
        $rdata = $this->_treeIds($id);
        return $this->pids;
    }

    private function _treeIds($id, $depth = 0) {
        $curr = $this->find($id);
        if (null === $curr) return null;
        $this->pids[] = $curr->getId();
        if ($curr->getParent() != null && $depth < 200)
            $this->_treeIds($curr->getParent()->getId(), ++$depth);
    }


}
