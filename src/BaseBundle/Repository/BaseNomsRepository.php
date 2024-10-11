<?php

namespace BaseBundle\Repository;

class BaseNomsRepository extends  \Doctrine\ORM\EntityRepository
{
	use \BaseBundle\Repository\BaseRepository;


	//parent ids
	private $pids = [];

	public function findBaseNom ($id){
	  $query = $this->getEntityManager()
	        ->createQuery(
	        'SELECT b, e FROM BaseBundle:BaseNoms b
	        JOIN b.extra e
	        WHERE b.id = :id'
	    )->setParameter('id', $id);

	  return $query->getOneOrNullResult();
	}

	public function updateType($new,$old){
		$qb = $this->getEntityManager()->createQueryBuilder();
		$q = $qb->update('BaseBundle:BaseNoms', 'u')
	        ->set('u.type', ':new')
	        ->where('u.type = :old')
	        ->setParameter('new', $new)
	        ->setParameter('old', $old)
	        ->getQuery();
			$p = $q->execute();
	}


	public function getTree($startId=null, $opened = [], $select = [], $maxDeph = 2, $depth = 0) {
		if (null!==$startId && !preg_match('/^[\d]+$/', $startId)) $startId=null;
		$noms = $this->findBy(['parent_id' => $startId], ['type'=>'asc','order'=>'asc']);
		$data = [];
		$selectedParent=false;
		$selectedType=false;
		if (isset($select['parent']) && preg_match('/^[\d]+$/', $select['parent']))
			$selectedParent = $select['parent'];
		if (isset($select['type']))
			$selectedType = $select['type'];


		foreach($noms as $nom) {
			$striked = (bool)(!$nom->getStatus());
			// Special case - if type is course.% - show the extra code_name (if any set)
			// refs #1209
			$extraName = '';
			if (substr($nom->getType()->getId(),0,7) == 'course.') {
				$extra = $nom->getExtraArray();
				if (isset($extra['code_name'])) {
					$extraName = $extra['code_name'] . ' - ';
				}
			}
			 $r = [
				'id' => $nom->getId(),
				'text' => ($striked?'<strike>':null) . $extraName . $nom->getName() . '('.$nom->getType()->getId().')' . ($striked?'</strike>':null),// <span><a href="https://www.yahoo.com" class="btn btn-sm btn-clean btn-icon btn-icon-md addChildNom"  data-parent-id="'.$nom->getId().'"> <i class="la la-plus-circle"></i></a></span>',
				'data' => [
					'type' => $nom->getType()->getId(),
					'parent_id'=>(null!==$nom->getParent()?$nom->getParent()->getId():null),
				],
				'children' => null,
			];
			//if (is_array($opened) && (in_array($nom->getParentId(), $opened) || in_array($nom->getId(), $opened)))
			if (is_array($opened) && in_array($nom->getId(), $opened)) // || (!in_array($nom->getId(), $opened) && in_array($nom->getParentId(), $opened)))
				$r['state']['opened'] = true;
			if ($selectedParent !== false && $nom->getId() == $selectedParent)
				$r['state']['selected'] = true;
			if ($selectedParent == false && $nom->getType()->getId() == $selectedType)
				$r['state']['selected'] = true;
			if ($this->count(['parent_id' => $nom->getId()]) > 0) {
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
	public function _getParentTree($id=null, $depth = 0) {
		//$em = $this->getEntityManager();
		//$root = $depth==0?true:false;
		$data = [];
		$nom = $this->find($id);
		$pid = $nom->getParent();
		$noms = $nom->getChildren()->toArray();// $this->findBy(['parent_id'=>$pid]);
		if ($pid!== null && sizeof($noms)>0) {
		//if ($noms) {
			$lastNom=null;
			$r=[];
			foreach ($noms as $nom) {
				$r[] = [
					'id' => $nom->getId(),
					'parentId' => ($nom->getParent()==null?'#':$nom->getParent()->getId()),
					'text' => $nom->getName(),
					'type' => $nom->getType()->getId(),
					//if in path - open
					'state' => ['opened'=>$id==$nom->getId(),],
				];
				$lastNom=$nom;
			}
			if ($lastNom != null && $lastNom->getParent() != null && $depth<150){
					//$this->f[] = $nom->getId();
					$r['parent'] = $this->_getParentTree($nom->getParent()->getId(), ++$depth);
				}
			$data = $r;
		} else {
			$data[] = [
					'id' => $nom->getId(),
					'parentId' => ($nom->getParent()==null?'#':$nom->getParent()->getId()),
					'text' => $nom->getName(),
					'type' => $nom->getType()->getId(),
					'state' => ['opened'=>true,],
				];
		}
		$this->pids[] = $nom->getId();
		return $data;
	}

	public function checkDuplicate($check) {
		return $this->findBy(['parent_id' => $check['parent_id'],'name' => $check['name'], 'type' => $check['type']]);
	}

	/**
	  * Finds extra values per type/parentId/name pair (only one can be used)
	  * @param $criteria array - array of find criterias. Accepted criterias are:
	  *  'nom' =>['type' => 'course.issue' ,'parentId' => 12,'name' => 'Issue 2'] - basenom find values: type (key), parentId, name; All or none can be passed.
	  * 'extra' => ['baseKey' => 'sys_current', 'baseValue' => 1] - can find in results of the nom; all or none of keys can be passed.
	  * @return find iterator returned
	  */
	public function findExtraValues(array $criteria) {
		$qb = $this->createQueryBuilder('bn');

		if (isset($criteria['fields']) && is_array($criteria['fields'])) {
			$qb->select(join(',', $criteria['fields']));
		} else
			$qb->select('bn,bne');
		$qb->leftJoin('bn.extra', 'bne');

		if (isset($criteria['nom']) && is_array($criteria['nom'])) {
			 foreach ($criteria['nom'] as $key => $val) {
			 	if (is_array($val))
					$qb->andWhere($qb->expr()->andX($qb->expr()->in('bn.'.$key, $val)));
				else
					$qb->andWhere($qb->expr()->andX($qb->expr()->eq('bn.'.$key, $val)));
			 }
		}
		if (isset($criteria['extra']) && is_array($criteria['extra'])) {
			 foreach ($criteria['extra'] as $key => $val) {
			 	if (!is_array($val))
			 		$val = [$val];
				 $qb->andWhere($qb->expr()->in(('bne.'.$key), $val));
			 }
		 }

		 //echo $qb->getQuery()->getDql();exit;
		 //return $qb->getQuery()->getArrayResult();
		 return $qb->getQuery()->getResult();
		 //return $qb->getQuery()->getDql();
		 $qb->where($qb->expr()->in('bn.type',['ac.subcategory','ac.subclasstype']));
         $result = $qb->getQuery()->getResult();
	}

	public function delRecursive(\BaseBundle\Entity\BaseNoms $nom) {
		return $this->_deleteRecursevly($nom);
	}
	private function _deleteRecursevly(\BaseBundle\Entity\BaseNoms $nom, $parent = null, $depth = 0) {
		$em = $this->getEntityManager();
		$children = $nom->getChildren()->toArray();//findBy(['parent_id' => $nom->getId()]);
		if (sizeof($children)>0) {
			//delete children
			foreach ($children as $child) {
				$this->_deleteRecursevly($child, $nom, ++$depth);
			}
		}
		//echo "RM: ".$depth. ' - '.$nom->getId();
		$em->remove($nom);
		if (null === $parent) {
			//echo 'FLUSH IT!';
			$em->flush();
			return true;
		}
		return false;
	}
}
