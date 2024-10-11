<?php

namespace BaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

use BaseBundle\Entity\City; 
use BaseBundle\Entity\EduInstitution;

class DefaultController extends Controller
{
    /**
     * @Route("/demo",name="demo")
     */
    public function indexAction()
    {
    	return $this->render('BaseBundle:Default:index.html.twig');
    }

    /**
     * @Route("/get_city/{id}",name="get_city")
     */
    public function getCityAction(Request $request, $id) {
        if ($request->isXmlHttpRequest()) {
           
            $result = [];
            $constrains = [
                'region' => $id,
            ];
            
            $temp = $this->getDoctrine()->getManager()->getRepository(City::class)->findBy($constrains, ['name'=>'ASC']);

            foreach ($temp as $k=> $tmp) {
                $val_label = $tmp->getName();
                $result[$k] = [
                    'id' => $tmp->getId(),
                    'value' => $val_label,
                ];
            }

            return new JsonResponse($result);
        }
    }

    /**
     * @Route("/get_edu_institution/{query}", name="edu_institution_autocomplete")
     */
    public function autocompleteAction(Request $request, $query = null) {
        if ($request->isXmlHttpRequest()) {
           
            $result = [];
            $constrains = [
                'name' => $query,
            ];
            
            $temp = $this->getDoctrine()->getManager()->getRepository(EduInstitution::class)->findInstitution($constrains);
            
            foreach ($temp as $k=> $tmp) {
                $val_label = $tmp->getFullName();
                $result[$tmp->getId()] = [
                    'id' => $tmp->getId(),
                    'value' => $val_label,
                ];
            }

            return new JsonResponse($result);
        }
    } 
    
    
	
}
