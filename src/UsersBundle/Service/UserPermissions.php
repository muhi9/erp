<?php
namespace UsersBundle\Service;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use FlightBundle\Entity\FlightCrew;

class UserPermissions {
	
	private $token;

    public function __construct(TokenStorage $tokenStorage) {
        $this->token =  $tokenStorage;
    }
    public function isSuperAdmin(){
        return $this->userIs('ROLE_SUPER_ADMIN');
    }
   	public function isAdmin(){
    	return $this->userIs('ROLE_ADMIN');
    }

    public function isOperator(){
    	return $this->userIs('ROLE_OPERATOR');
    }

    public function isInstructor(){
    	return $this->userIs('ROLE_INSTRUCTOR');
    }

    public function isClient(){
        return $this->userIs('ROLE_CLIENT');
    }

    public function isStudent(){
    	return $this->userIs('ROLE_STUDENT');
    }

    public function isUser(){
        return $this->userIs('ROLE_USER');
    }

    private function userIs($role){
        return in_array($role, $this->token->getToken()->getUser()->getRoles())?true:false;
    }

     /** 
     * @param (array) $roles = [admin, operator, instructor, student, client]
     * @return bool
     */
    public function accessLevel($roles = []) {
        $access = false;
        foreach ($roles as $role) {
            $role = 'ROLE_'.strtoupper($role);
            if($this->userIs($role)){
               $access = true; 
            }
        }
        return $access;
    }

    /**
    * @param (entity) $entity Flight||Booking
    * @param (bool) $instructor_user = false 
    * @return bool
    *
    */
    public function positionInFlight($entity, $instructor_user=false) {
        $access = false;
        if(!$this->accessLevel(['admin','operator'])) {
            if($this->isInstructor()) {
                if(!empty($entity)&&!empty($entity->getCrew())) {
                    foreach ($entity->getCrew() as $key => $crew) {
                        if($crew->getUser() == $this->token->getToken()->getUser()->getInfo()
                            && (in_array($crew->getPosition()->getBnomKey(), [FlightCrew::GROUND_INSTRUCTOR, FlightCrew::INSTRUCTOR, FlightCrew::PIC]))
     
                            ) {
                            $access = true;
                        }      
                    }
                }

                if($instructor_user) {
                    if($entity->getUser() == $this->token->getToken()->getUser()->getInfo()){
                        $access = true;
                    }
                }

            } else {
                //role student, client, dual, pic
                foreach ($entity->getCrew() as $key => $crew) {
                    if($crew->getUser() == $this->token->getToken()->getUser()->getInfo()
                        && (in_array($crew->getPosition()->getBnomKey(), [FlightCrew::PILOT, FlightCrew::DUAL, FlightCrew::PIC]))
 
                        ) {
                        $access = true;
                    }      
                }
                /* 
                
                if($entity->getUser() == $this->token->getToken()->getUser()->getInfo()) {
                    $access = true;
                } */
            }
        }else{
            $access = true; //if user is Admin || Operator    
        }
        return $access;
    }
}
