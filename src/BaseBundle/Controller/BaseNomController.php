<?php

namespace BaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

use BaseBundle\Entity\BaseNoms;

use BaseBundle\Entity\BaseNomsExtra;
use BaseBundle\Entity\NomType;
use BaseBundle\Form\BaseNomsType as BaseNomsForm;


class BaseNomController extends Controller
{
    use NomTrait;

    /**
     * @Route("/adm/base_nom/", name="basenom_list")
     */
    public function indexAction(Request $request)
    {
        return $this->baseIndex($request);
        $data = $this->baseIndex($request);
        if($request->isXmlHttpRequest()) {
            return $data;
        }
        return $this->render('@Base/BaseNom/index.html.twig', $data);
    }

    /**
     * @Route("/base_nom/loadChild1", name="basenom_load_child1")
     */
    public function loadChild1(Request $request)
    {
        return $this->baseLoadChild($request);
    }

    /**
     * @Route("/adm/base_nom/reorder", name="basenom_reorder")
     */
    public function reoderAction(Request $request)
    {
        return $this->baseReorder($request);
    }

    /**
     * @Route("/base_nom/getlist", name="basenom_get_list")
     */
    public function getListAction(Request $request)
    {
        return $this->baseGetList($request);
    }

    /**
     * @Route("/adm/base_nom/status/{id}", defaults={"id"=null}, name="nom_status")
     */
    public function statusAction(BaseNoms $basenom)
    {
        return $this->baseStatus($basenom);
    }

    /**
     * @Route("/base_nom/tree1", name="nom_tree_ajax")
     */
    public function nomTree1Action(Request $request) {
        return $this->baseNomTree1($request);
    }


    /**
     * @Route("/base_nom/tree", name="nom_tree")
     */
    public function nomTreeAction(Request $request) {
        return $this->baseNomTree($request);
    }



    /**
     * @Route("/adm/base_nom/add", name="basenom_add")
     */
    public function addAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $parent = $request->get('parent');
        $loadType = $request->get('type');

        $basenom = new BaseNoms();
        $formProps = [
            'doctrine' => $this->getDoctrine(),
            'action' => $this->generateUrl($request->get('_route')),
            'treeSettings' => [
                'showTree' => 'true', // hides the tree in accordeon
                'disableSelected' => 'true', // if true, all tree nom that are selected are disabled
                'hideLastEl' => 'true', // hides last tree element - the one edited at the moment.
            ],
            'type' => $loadType,
            'treeType' => 'add',
        ];

        if (!empty($parent) && preg_match('/^[\d]+$/', $parent)) {
            $parent = $this->getDoctrine()->getRepository(BaseNoms::class)->find($parent);
            if (null !== $parent)
                $formProps['parent'] = $parent;
        }

        $form = $this->createForm(BaseNomsForm::class, $basenom,$formProps);
        $data['form'] = $form->createView();

        $form->handleRequest($request);
        if($request->isXmlHttpRequest()) {
            $notValid = $this->xhrValidateForm($form);
            if (isset($notValid['formErrors']) || $request->get('validationRequest') == 'only')
                return new JsonResponse($notValid);

           
            $pdata = $request->get('BaseBundle_basenoms');
            $parents = isset($pdata['parent'])&&is_array($pdata['parent'])?$pdata['parent']:null;

            if (!empty($parents)) {
                $parent = null;
                foreach ($parents as $prn) {
                    if(!is_array($prn)){
                        $parent = $prn;
                        continue;
                    }

                    $pbasenom = new BaseNoms();
                    $pbasenom->setParent($parent);
                    $pbasenom->setType($parent->getType());
                    $pbasenom->setName($parent->getName());
                    $em->persist($pbasenom);
                    $em->flush();
                    $parent = $pbasenom;//->getId();

                }
            }


            if(!empty($pdata)){
               
                $em->persist($basenom);
                $em->flush();
                $id = $basenom->getId();
                if ($id > 0) {
                    if ($request->request->get('_action') == 'redirect')
                        return $this->redirectToRoute('basenom_list');
                    $redirectUrl = $this->generateUrl('basenom_list', [
                        'type' => $basenom->getType()->getId(),
                        'lpid' => $basenom->getId()
                    ]);
                    $returnPath = $request->get('returnPath');
                    if (!empty($returnPath)) {
                        $redirectUrl = str_replace('__newid__', $basenom->getId(), $returnPath);
                    }
                    return new JsonResponse(['success' => true, 'id' => $id, 'name' => $basenom->getName(), 'type' => $basenom->getType()->getId(), 'parent' => ($basenom->getParent() !==null ? $basenom->getParent()->getId():null), 'redirect' => $redirectUrl]);
                    //return $this->redirectToRoute('basenom_list');
                } else {
                    return new JsonResponse(['error' => ['Could not find save nom.']]);
                }
            }

            //return $this->render('@Base/BaseNom/add_minimal.html.twig', $data);
            return new JsonResponse('shithitfan');
        }
        // no ajax
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($basenom);
            $em->flush();

            $returnPath = $request->get('returnPath');
            if (!empty($returnPath)) {
                $returnPath = str_replace('__newid__', $basenom->getId(), $returnPath);
                return $this->redirectUrl($returnPath);
            }

            return $this->redirectToRoute('basenom_list');
        }


        //if ($request->get('loadtype') == 'modal')
        //else
        return $this->render('@Base/BaseNom/add.html.twig', $data);
    }



    /**
     * @Route("/adm/base_nom/edit/{id}", defaults={"id"=null}, name="basenom_edit")
     */
    public function editAction(Request $request,BaseNoms $basenom)
    {
        $em = $this->getDoctrine()->getManager();
        $formProps = [
            'doctrine' => $this->getDoctrine(),
            'action' => $this->generateUrl($request->get('_route'), ['id' => $basenom->getId()]),
            'treeSettings' => [
                'showTree' => 'false', // hides the tree in accordeon
                'disableSelected' => 'true', // if true, all tree nom that are selected are disabled
                'hideLastEl' => 'true', // hides last tree element - the one edited at the moment.
            ],
            'nomId' => $basenom->getId(),
        ];
        $form = $this->createForm(BaseNomsForm::class, $basenom, $formProps);

        $form->handleRequest($request);

        $notValid = $this->xhrValidateForm($form);
        if (isset($notValid['formErrors']) || $request->get('validationRequest') == 'only')
            return new JsonResponse($notValid);

        if ($form->isSubmitted() && $form->isValid()) {
            //echo 'd: '.$basenom->getName();
            //echo "\nnow we save";exit;
            
            $em = $this->getDoctrine()->getManager();

            

            $em->persist($basenom);
            $em->flush();

            return $this->redirectToRoute('basenom_list', [
                'lpid'=> $basenom->getParent()->getId(),
                'type' => $basenom->getType()->getId()
            ]);
        }

        $data['form'] = $form->createView();
        return $this->render('@Base/BaseNom/edit.html.twig', $data);
    }


    /**
     * @Route("/adm/base_nom/delete/{id}", defaults={"id"=null}, name="basenom_delete")
     */
    public function deleteAction(BaseNoms $basenom)
    {
        if(!in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
            return new JsonResponse(['errorMsg'=>'No correct permissions.']);
        }
        //return $this->redirectToRoute('basenom_list');
        $rmRecusrive = $this->getDoctrine()->getRepository(BaseNoms::class)->delRecursive($basenom);
        if ($rmRecusrive) {
            return new JsonResponse(['success' => true, 'msg'=>'Node with all it\'s children deleted.']);
        } else {
            return new JsonResponse(['errorMsg'=>'Error occurend when deleting tree node.']);
        }

        $errors = false;
        $session = $this->get('session');
        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($basenom);
        try {
            $em->flush();
        } catch (\Exception $e) {
           $errors[] =  $this->get('translator')->trans('exception.onDelete').$e->getMessage();
        }

        if($errors){
            $session->set('errors', $errors);
        }
    }


    /**
     * @Route("/adm/base_nom/addNew", name="basenom_add_new")
     */
    public function addNew(Request $request)
    {
        $basenom = new BaseNoms();
        $em = $this->getDoctrine()->getManager();

        if($request->isXmlHttpRequest()) {
            $param = $request->get('data')!=null?$request->get('data'):null;
            if(empty($param)){
                return new JsonResponse('false');
            }

            if(isset($param['p'])&&!empty($param['p'])){
                $parent = $em->getRepository(BaseNoms::class)->find($param['p']);
                if (null !== $parent) {
                    $basenom->setParent($parent);
                }
            }
            if(isset($param['t'])&&!empty($param['t'])){
                $basenom->setType($em->getRepository(NomType::class)->find($param['t']));
            }
            if(isset($param['val'])&&!empty($param['val'])){
                $basenom->setName($param['val']);
            }
            $notValid = $this->xhrValidateEntity($basenom);
            if (isset($notValid['formErrors']) || $request->get('validationRequest') == 'only')
                return new JsonResponse($notValid);

          //  echo "D: ".$basenom->getName() . ';-> '.$basenom->getParentId();exit;
            $em->persist($basenom);
            $em->flush();
            $id = $basenom->getId();
            if ($id > 0) {
                return new JsonResponse(['success' => true, 'id' => $id, 'type' => $basenom->getType()->getId(), 'bnomkey' =>null]);
            } else {
                return new JsonResponse(['error' => ['Could not find save nom.']]);
            }

        }else{
            return new JsonResponse('not request');
        }

    }


    /**
     * @Route("/base_nom/loadChild", name="basenom_load_child")
     */
    public function loadChild(Request $request)
    {
        $extra = false;
        $em = $this->getDoctrine()->getManager();
        $criteries['status'] = 1;
        if($request->isXmlHttpRequest()) {

            $param = $request->get('data');
            if(isset($param['criteries']['id'])&&!empty($param['criteries']['id'])){
                $criteries['id'] = $param['criteries']['id'];
            }
            if(isset($param['criteries']['parent'])&&!empty($param['criteries']['parent'])){
                $criteries['parent'] = $param['criteries']['parent'];
            }
            if(isset($param['criteries']['parent'])&&!empty($param['criteries']['parent'])){
                $criteries['parent'] = $param['criteries']['parent'];
            }
            if(isset($param['criteries']['type'])&&!empty($param['criteries']['type'])){
                 $criteries['type'] = $param['criteries']['type'];
            }
            if(isset($param['criteries']['type_id'])&&!empty($param['criteries']['type_id'])) {
                 $criteries['type'] = $param['criteries']['type_id'];
            }
            
            //print_r($criteries);exit;
            $besnomChild = $this->getDoctrine()->getRepository(BaseNoms::class)->findBy($criteries, ['order' => 'ASC']);
            $result = ['data' => [], ];
            foreach ($besnomChild as $key => $value) {
                $tmp = [];
                $tmp['id'] = $value->getId();
                $tmp['value'] = $value->getName();
                $tmp['type'] = $value->getType()->getId();
                $tmp['bnomkey'] = $value->getBnomKey();
                if ($value->getParent() !== null)
                    $tmp['parent'] = $value->getParent()->getId();
                else
                    $tmp['parent'] = '';
                
                $result['data'][] = $tmp;

            }

            return new JsonResponse($result);


        } else {
              return new JsonResponse(['error' => 'Not a proper request']);
        }
    }

}
