<?php

namespace UsersBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use UsersBundle\Entity\User;
use UsersBundle\Entity\UserPersonalInfo;

class ProfileListener {
    
    private $container;
    
    public function __construct($container)
    {
        $this->container = $container;
    }    
    
    public function postPersist(LifecycleEventArgs $args) {
        $entity = $args->getEntity();

        if ($entity instanceof UserPersonalInfo) {
             

            //create fos_user
            
            if(!empty($entity->getUser())) {
                $roles[] = 'ROLE_OPERATOR';
                
                $entity->getUser()->setRoles($roles);
                $args->getEntityManager()->persist($entity);
                $args->getEntityManager()->flush();
            }           
        }
    }
}