<?php

namespace BaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use BaseBundle\Entity\NomType;
use BaseBundle\Entity\BaseNoms;
use BaseBundle\Form\NomForm;


class NomTypeController extends Controller
{
    use \BaseBundle\Controller\DataGridControllerTrait;
    use \BaseBundle\Controller\FormValidationControllerTrait;


    public $dotSeparator = '__________';
    /**
     * @Route("/adm/nom_type/new", name="nom_type_new")
     */
    public function newAction(Request $request)
    {
        $data = ['dotSeparator'=>$this->dotSeparator];

        $nomtype = new NomType();
        $em = $this->getDoctrine()->getManager();
        $parent = str_replace($this->dotSeparator,'.',$request->get('parent'));
        $parent = $em->getRepository(NomType::class)->find($parent);
        $form = $this->createForm(NomForm::class, $nomtype,['em'=>$em,'parent'=>$parent] );
        //$form = $this->createForm(NomForm::class, $nomtype,['em'=>$em]);

        $form->handleRequest($request);

        if ($form->isSubmitted()) { // && $form->isValid()) {
            $notValid = $this->xhrValidateForm($form);
            if (isset($notValid['formErrors']) || $request->get('validationRequest') == 'only')
                return new JsonResponse($notValid);

           $key =$form->getData()->getNameKey();

            if($this->checkKey($key)){
               return new JsonResponse(['formErrors' => ['nom_form[nameKey]' => $key . ' duplicates']]);
            }else{
                $exf = null;
                if($request->get('noms')!=null){
                    $data = $request->get('noms');
                    if($data['extraField']){
                        $exf = $data['extraField'];
                    }
                }

                $nomtype->setExtraField($exf);
                $em->persist($nomtype);
                $em->flush();

                return $this->redirectToRoute('nom_type_index');
            }

        }
        $data['form'] = $form->createView();

        return $this->render('BaseBundle:NomType:new.html.twig', $data);
    }

    /**
     * @Route("/adm/nom_type/edit/{id}", name="nom_type_edit")
     */
    public function editAction(Request $request, $id = null)
    {
        $data = ['dotSeparator'=>$this->dotSeparator];
        $em = $this->getDoctrine()->getManager();
        $parent = null;
        $id = str_replace($this->dotSeparator,'.',$id);
        $nomtype =  $em->getRepository(NomType::class)->find($id);

        if($nomtype!=null&&$nomtype->getParent()!=null){
            $parent = $nomtype->getParent();
        }
        $form = $this->createForm(NomForm::class, $nomtype,['em'=>$em,'parent'=>$parent] );

        $form->handleRequest($request);

        if ($form->isSubmitted()) { // && $form->isValid()) {
            $notValid = $this->xhrValidateForm($form);
            if (isset($notValid['formErrors']) || $request->get('validationRequest') == 'only')
                return new JsonResponse($notValid);

           $key =$form->getData()->getNameKey();
            if($this->checkKey($key,$id)){
               return new JsonResponse(['formErrors' => ['nom_form[nameKey]' => $key . ' duplicates']]);
            }else{
                $exf = null;
                if($request->get('noms')!=null){
                    $noms = $request->get('noms');
                    if($noms['extraField']){
                        $exf = $noms['extraField'];
                    }
                }

                $nomtype->setExtraField($exf);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($nomtype);
                $entityManager->flush();
                //update basenom key
                if($key!==$id){
                    $bntypes = $entityManager->getRepository(BaseNoms::class)->updateType($key,$id);
                }

                return $this->redirectToRoute('nom_type_index',['start'=>10,'length'=>10]);
            }
        }

        $data['form'] = $form->createView();

        return $this->render('BaseBundle:NomType:edit.html.twig', $data);
    }
    /**
     * @Route("/adm/nom_type/delete/{id}", name="nom_type_delete")
     */
    public function deleteAction(Request $request, $id = null)
    {

        return new JsonResponse(['errorMsg' => 'Server refused deleteion of node.']);
        $data = ['dotSeparator'=>$this->dotSeparator];
        $em = $this->getDoctrine()->getManager();
        $parent = null;

        $id = str_replace($this->dotSeparator,'.',$id);
        $nomtype =  $em->getRepository(NomType::class)->find($id);
        if($nomtype!=null&&$nomtype->getParent()!=null){
            $parent = $nomtype->getParent();
        }
        $form = $this->createForm(NomForm::class, $nomtype,['em'=>$em,'parent'=>$parent] );
        $data['form'] = $form->createView();
        return $this->render('BaseBundle:NomType:edit.html.twig', $data);
    }
    /**
     * @Route("/adm/nom_type/copy/{id}", name="nom_type_copy")
     */
    public function copyAction(Request $request, $id = null)
    {
       
        $data['id'] = $id;
        return $this->render('BaseBundle:NomType:copy.html.twig', $data);
    }
    /**
     * @Route("/adm/nom_type/", name="nom_type_index")
     */
    public function indexAction(Request $request)
    {
        $defWhere = [];

        if (!empty($request->get('snameKey'))) {
            $defWhere['nameKey'] = str_replace($this->dotSeparator, '.', $request->get('snameKey'));
        }

        if (!empty($request->get('spnameKey'))) {
            $defWhere['parentNameKey1'] = str_replace($this->dotSeparator, '.', $request->get('spnameKey'));
        }

        $table = $this->dataTable($request, NomType::class, [
            'columns' => ['name','type'],
            'search'=>'nameKey',
            'count'=>'nameKey',
//            'order'=>['nameKey'=>'ASC'],
            'order' => ['col'=>1, 'sort'=>'ASC'],
            'group_by' => ['field'=>'nameKey'],
            'searchMap'=>['status'=>'eq'],
            'defWhere' => $defWhere,
            'pk_field' => 'nameKey',
        ], [
            'name' => [
                'title'     => 'Name',
                'search' => ['type'=>'input','value'=>null],
                'orderable'=> true,
            ],
            'nameKey' => [
                'title'     => 'Key',
                'search' => ['type'=>'input','value'=>null],
            ],
            'parentNameKey' => [
                'title'     => 'Parent Key',
                'search' => ['type'=>'input','value'=>null],
            ],
            'status' => [
                'title' => 'Status',
                'search' => ['type'=>'select','value'=>['off'=>'off','on'=>'on']],
            ],
            'descr' => [
                'title' => 'Description',
            ],
        ]);

        if (is_array($table)) {
            $nomtree_filters = [];//'test'=>1];
            if ($request->get('startIds')) {
                $loadParents = $request->get('startIds');
                $loadParents = str_replace($this->dotSeparator, '.', $loadParents);
                $nomtree_filters = ['startIds' => $loadParents];
            }
        }

        return is_array($table) ? $this->render('BaseBase/NomType/index.html.twig', ['table'=>$table, 'nomtree_filters'=>$nomtree_filters, 'dotSeparator'=>$this->dotSeparator]) : $table;






        //$q= json_encode(array("Aircraft category"));
        $tableData = [];
        $data = ['dotSeparator'=>$this->dotSeparator];

        $fields = ['name','nameKey','parentNameKey','status','descr'];

        $defWhere = [];

        if (!empty($request->get('snameKey'))) {
            $defWhere['nameKey'] = str_replace($this->dotSeparator, '.', $request->get('snameKey'));
        }

        if (!empty($request->get('spnameKey'))) {
            $defWhere['parentNameKey1'] = str_replace($this->dotSeparator, '.', $request->get('spnameKey'));
        }

        $gridDatas = $this->dataGridPrepareHandler($request, NomType::class, [
                'columns' => ['name','type'],
                'search'=>'nameKey',
                'count'=>'nameKey',
                'order'=>['nameKey'=>'ASC'],
                'searchMap'=>['status'=>'eq'],
                'defWhere' => $defWhere,
        ]);


        $showData['list'] = [];
        foreach ($gridDatas['list'] as $key => $object) {
                $showData['list'][$key]['name'] = $object->getName();
                $showData['list'][$key]['nameKey'] = $object->getNameKey();
                $showData['list'][$key]['parentNameKey'] = $object->getParentNameKey();
                $showData['list'][$key]['status'] = $object->getStatus();
                $showData['list'][$key]['descr'] = $object->getDescr();
        }

        foreach($showData['list'] as $row) {
                $row['actions'] = null;
                $data[] = $row;
        }

        if($request->isXmlHttpRequest()) {


            $tableData = [
                "iTotalRecords" => $gridDatas['limit'],
                "iTotalDisplayRecords" => $gridDatas['count'],
                "sEcho" => 0,
                "sColumns" => "",
                "aaData" => $showData['list'],
            ];

            return new JsonResponse($tableData);
        } else {
            $table = [
                'name' => NomType::class,
                'order'=>['col'=>0,'sort'=>'ASC'],
                'ajax_data_url' => $this->generateUrl('nom_type_index', array('slug' => 'ajax-index')),
                'action_links' => [
                    'edit_link' => [
                        'html' => '<a href="'
                            . $this->generateUrl('nom_type_edit', array('pk_field'=> '{{{pk_value}}}'))
                            . '" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit"> <i class="la la-edit"></i></a>',
                    ],
                    /*'delete_link' => [
                        'html' => '<a href="'
                            . $this->generateUrl('nom_type_delete', array('pk_field'=> '{{{pk_value}}}'))
                            . '" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Delete"> <i class="la la-remove"></i></a>',
                    ]*/
                ],
                'pk_field' => 'nameKey',
                'fields' => [
                    'name'=>'Name',
                    'nameKey'=>'Key',
                    'parentNameKey'=>'Parent Key',
                    'status'=>'Status',
                    'descr'=>'Description',
                    //'actions'=>'Action',
                ],
                'searchFields' => [
                    '0'=>['type'=>'input','value'=>null,],
                    '1'=>['type'=>'input','value'=>null],
                    '2'=>['type'=>'input','value'=>null],
                    '3'=>['type'=>'select','value'=>['off'=>'off','on'=>'on']],
                    '4'=>['type'=>null,'value'=>null],
                    //'actions'=>'Action',
                ],
                'custom_column_defs' => [
                    'name' => [
                        'orderable'=> 'true',
                        'searchable'=> 'true',

                    ],
                    'nameKey' => [
                        'orderable'=> 'false',
                        'searchable'=> 'true',
                    ],
                    'parentNameKey' => [
                        'orderable'=> 'false',
                        'searchable'=> 'true',
                    ],
                    'status' => [
                        'orderable'=> 'false',
                        'searchable'=> 'true',
                    ],
                ],

            ];
            $data['nomtree_filters'] = [];//'test'=>1];
            $data['table'] = $table;
            if ($request->get('startIds')) {
                $loadParents = $request->get('startIds');
                //print_r($loadParents);exit;
                $loadParents = str_replace($this->dotSeparator, '.', $loadParents);
                $data['nomtree_filters'] = ['startIds' => $loadParents];
            }
            return $this->render('BaseBundle:NomType:index.html.twig', $data);
        }
    }


    /**
     * @Route("/adm/nom_type/load-nom-tree/", name="loadNomTree")
     */

    public function loadNomTree(Request $params){
        /*
            return format:
            {
                "last":{
                    "parent":"module.root=\u003Ecourse.course=\u003Ecourse.subCourse=\u003Ecourse.issue=\u003Ecourse.part=\u003Ecourse.module=\u003Ecourse.subjectPhase", // not used??
                    "label":"Lesson\/Exercise",
                    "type":"course.lessonExercise",
                    "length":0 // 0 by default 1 when no parents of current element
                },
                "result":{
                    "module.root":{
                        "parent":null,
                        "label":"Root Module name",
                        "type":"course.lessonExercise",
                        "length":0 // 0 by default 1 when no parents of current element
                    },

                    "course.course":{
                        "parent":"module.root", // not used???
                        "label":"Course Name",
                        "type":"course.lessonExercise",
                        "length":0 // 0 by default 1 when no parents of current element
                    },
                    ....
                },
                "childs":{
                    "module.root":"course.course",
                    "course.course":"course.subCourse",
                    ...
                    "course.module":"course.subjectPhase",
                    "course.subjectPhase":null},
                "extraFields":{"code_name":""}

            }
        */
            $em = $this->getDoctrine()->getManager();
            $criteries = $params->get('data');
            $reverse = ($params->get('reverse')!=null?$params->get('reverse'):false);
            $nomtype =  $em->getRepository(NomType::class)->find($criteries);
            $parentsData = $childData = $result = [];


            if(empty($reverse)){
                if(null !== $nomtype && $nomtype->getParent()!==null) {
                    $nomParent = $nomtype->getParent();
                    $result['last'] = [
                        'label'=>$nomtype->getName(),
                        'type'=>$nomtype->getNameKey(),
                        'parent'=> ($nomParent!==null?$nomParent->getId():null),
                        'length'=>0
                    ];
                    $maxLimit=150;
                    $limit=0;
                    $prevParent = null;
                    //$findParent = $nomtype; //- this way we include current nomtype in result and childs. old code didn't do it.
                    $findParent = $nomtype->getParent();
                    while (true) {
                        if ($limit>$maxLimit) break;
                        //if (null !== $findParent) {
                            //$parentsData[] = $findParent;
                            $fpp = $findParent->getParent();
                            $result['result'][$findParent->getId()] = [
                                'label' => $findParent->getName(),
                                'type' => $findParent->getId(),
                                'parent' => (null!==$fpp?$fpp->getId():null),
                                'length' => 0,
                            ];

                            if (null !== $prevParent)
                                $childData[$findParent->getId()] = $prevParent->getId();
                            else
                                $childData[$findParent->getId()] = null;
                        //}
                        $limit++;
                        $prevParent = $findParent;
                        $findParent = $findParent->getParent();
                        if (null === $findParent) break;
                    }

                    $childData = array_reverse($childData);
                    $result['result'] = array_reverse($result['result']);
                } else {
                    $result['result'][$nomtype->getId()] =
                        [
                            'label'=>$nomtype->getName(),
                            'type'=>$nomtype->getNameKey(),
                            'parent'=>null,
                            'length'=>1
                        ];
                }
            } else {
                echo "TODO: reimplement reverse!!!";exit;
                /*
                $parentsData = [];
                $treeChild = $this->get('base_repo')->nomTypeList(['criteries'=>['slike'=>['parentNameKey'=>$criteries]]]);

                $hasCh = $em->getRepository(NomType::class)->getParentNameKey($treeChild[0]->getParentNameKey1());
                $lastElement = false;
                while ($hasCh!=null) {
                   $treeChild = $this->get('base_repo')->nomTypeList(['criteries'=>['slike'=>['parentNameKey'=>$treeChild[0]->getId()]]]);
                    if($treeChild!=null){
                      $lastElement = ['name'=>$treeChild[0]->getId(),'parents'=>$em->getRepository(NomType::class)->getParentNameKey($treeChild[0]->getParentNameKey())];
                    }
                   $hasCh = $treeChild;

                }
                if(!empty($lastElement)){
                    $parents = explode('=>', $lastElement['parents']);
                    unset($parents[1]);
                    unset($parents[0]);
                    foreach ($parents as $key => $value) {
                             $parentsData[] =  $em->getRepository(NomType::class)->find(trim($value));
                    }
                    $parentsData[] =  $em->getRepository(NomType::class)->find(trim($lastElement['name']));
                    foreach ($parentsData as $key => $parent) {
                        $result['result'][$parent->getId()] = ['parent'=>$parent->getParentNameKey(),'label'=>$parent->getName(),'type'=>$parent->getNameKey(),'length'=>0];
                    }
                }
                */
            }


            $result['childs'] = $childData;
            $extras = $nomtype->getExtraField();
            if (sizeof($extras)>0) {
                $extra = [];
                foreach ($extras as $ex) {
                    $extra[$ex] = '';
                }
            }
            $result['extraFields']= !empty($extra)?$extra:null;
            return new JsonResponse($result);
    }


    /**
     * @
     Route("/load-nom-tree1/", name="loadNomTree1")

    public function loadNomTree1(Request $params){

            $nomtype = new NomType();
            $em = $this->getDoctrine()->getManager();
            $criteries = $params->get('data');
            $reverse = ($params->get('reverse')!=null?$params->get('reverse'):false);
            $nomtype =  $em->getRepository(NomType::class)->find($criteries);
            $parentsData = $nomtype;
            $childData = [];
            if(empty($reverse)){

                if($nomtype->getParentNameKey1()!==null){
                    $parentsData = [];
                    $result['last'] = ['parent'=>$em->getRepository(NomType::class)->getParentNameKey($nomtype->getParentNameKey1()),'label'=>$nomtype->getName(),'length'=>0];
                    $child = $parents = explode('=>',$em->getRepository(NomType::class)->getParentNameKey($nomtype->getParentNameKey1()));
                    foreach ($parents as $key => $value) {
                         $parentsData[] =  $em->getRepository(NomType::class)->find(trim($value));
                    }
                    foreach ($parents as $key => $value) {
                         $childData[trim($value)] = isset($child[$key+1])?trim($child[$key+1]):null;
                    }

                    foreach ($parentsData as $key => $parent) {
                        $result['result'][$parent->getId()] = ['parent'=>$em->getRepository(NomType::class)->getParentNameKey($parent->getParentNameKey1()),'label'=>$parent->getName(),'length'=>0];
                    }
                }else{
                    $result['result'][$parentsData->getId()] = ['parent'=>null,'label'=>$parentsData->getName(),'length'=>1];
                }
            }else{
                $parentsData = [];
                $treeChild = $this->get('base_repo')->nomTypeList(['criteries'=>['slike'=>['parentNameKey'=>$criteries]]]);

                $hasCh = $em->getRepository(NomType::class)->getParentNameKey($treeChild[0]->getParentNameKey1());
                $lastElement = false;
                while ($hasCh!=null) {
                   $treeChild = $this->get('base_repo')->nomTypeList(['criteries'=>['slike'=>['parentNameKey'=>$treeChild[0]->getId()]]]);
                    if($treeChild!=null){
                      $lastElement =['name'=>$treeChild[0]->getId(),'parents'=>$em->getRepository(NomType::class)->getParentNameKey($treeChild[0]->getParentNameKey1())];
                    }
                   $hasCh = $treeChild;

                }
                if(!empty($lastElement)){
                    $parents = explode('=>', $lastElement['parents']);
                    unset($parents[1]);
                    unset($parents[0]);
                    foreach ($parents as $key => $value) {
                             $parentsData[] =  $em->getRepository(NomType::class)->find(trim($value));
                    }
                    $parentsData[] =  $em->getRepository(NomType::class)->find(trim($lastElement['name']));
                    foreach ($parentsData as $key => $parent) {
                        $result['result'][$parent->getId()] = ['parent'=>$em->getRepository(NomType::class)->getParentNameKey($parent->getParentNameKey1()),'label'=>$parent->getName(),'length'=>0, 'extraFields' => (!empty($parent->getExtraField())?$parent->getExtraField():null)];
                    }
                }

            }


            //$result['childs']=$childData;
            //$result['extraFields']= !empty($nomtype->getExtraField())?$nomtype->getExtraField():null;
            return new JsonResponse($result);
    }
     */

    /**
     * @Route("/nom_type/read-nom-list/", name="ReadNomList")
     */
    public function readNomList(Request $params){
            $nomtype = new NomType();
            $em = $this->getDoctrine()->getManager();
            $where = $nomType = null;
            if(!empty($params->get('loadId'))) {
                $nomType = $em->getRepository(BaseNoms::class)->findOneBy(['id' => $params->get('loadId')]);
                $nomtype =  $em->getRepository(NomType::class)->findBy(['nameKey' => $nomType->getChildren()[0]->getType()->getNameKey()]);
            } else {
                $nomtype =  $em->getRepository(NomType::class)->findAll();

            }


            foreach ($nomtype as $key => $v) {
                $result[$v->getId()] = [
                    'name'=>$v->getName(),
                    'parent'=> ($v->getParent()!=null?$v->getParent()->getId():null),
                    //'parent'=>$em->getRepository(NomType::class)->getParentNameKey($v->getParentNameKey()),
                    'desc'=>$v->getDescr()
                ];
            }
            return new JsonResponse($result);
    }

    private function checkKey($newKey, $oldKey=null){
            $nomtype = new NomType();
            $em = $this->getDoctrine()->getManager();
            if(!empty($oldKey)&&$newKey==$oldKey){
                $nomtype=null;

            }else{
                $nomtype =  $em->getRepository(NomType::class)->find($newKey);
            }

            return $nomtype;
    }


     /**
     * @Route("/adm/nom_type/get-nom-type/", name="getNomType")
     */
    public function getNomType(Request $params){
        $nomtype = new NomType();
        $em = $this->getDoctrine()->getManager();
        $criteries = $params->get('data');
        $checkBaseNom = $params->get('check');

        $baseNoms = null;
        if($checkBaseNom){
            $baseNoms =  $em->getRepository(BaseNoms::class)->findBy($checkBaseNom);
        }

        if($checkBaseNom&&empty($baseNoms)){
            return new JsonResponse(0);
        }
        $nomtype =  $em->getRepository(NomType::class)->find($criteries);
        $result =0;
        if($nomtype!=null){
            //search child
            $child = $this->get('base_repo')->nomTypeList(['criteries'=>['slike'=>['parentNameKey'=>$criteries]]]);
            $child = !empty($child)?$child[0]->getId():0;
            //search child
            $id = str_replace('.','', $nomtype->getId());
            $result = ['type'=>$nomtype->getId(),'id'=>$id,'parent'=>null,'label'=>$nomtype->getName(),'length'=>1,'child'=>$child,'extraFields'=>$nomtype->getExtraField()];
        }
       // $result['childs']=$childData;
        //$result['extraFields']= !empty($nomtype->getExtraField())?$nomtype->getExtraField():null;
        return new JsonResponse($result);

    }

     /**
     * @Route("/adm/nom_type/get-nom-type2/", name="getNomType2")
     */
    public function getNomType2(Request $params){
        $em = $this->getDoctrine()->getManager();
        $findNomKey = $params->get('nomKey');
        $findBaseNom = $params->get('findBaseNom');
        $data = [
            'baseNom' => null,
            'nomType' => null,
        ];

        $baseNoms = null;
        if($findBaseNom) {
            $baseNoms =  $em->getRepository(BaseNoms::class)->findBy($findBaseNom);
            $data['baseNom'] = $baseNom;
        }
        if (null === $baseNoms) {
            if (empty($findNomKey))
                throw new \Exception("No findomkey set!");
            $nomtype =  $em->getRepository(NomType::class)->find($findNomKey);
        } else {
            $nomtype = $baseNoms->getType();
        }
        if($nomtype != null) {
            //search child
            $child = $nomtype->getChildren();//$em->getRepository(NomType::class)->findBy(['parentNameKey1'=>$findNomKey]);
            $childs = [];
            foreach($chid as $ch)
                $childs[] = $ch->getId();
            $data['nomType'] = [
                'type'=>$nomtype->getId(),
                'label'=>$nomtype->getName(),
                'child'=>$childs,
                'extraFields'=>$nomtype->getExtraField()];
        }
       // $result['childs']=$childData;
        //$result['extraFields']= !empty($nomtype->getExtraField())?$nomtype->getExtraField():null;
        return new JsonResponse($result);

    }


     /**
     * @Route("/nom_type/get-nom-type1/", name="getNomType1")

     TODO: write datacheck to count results for dropdown.
     another thing - we must return >1 result if we have optionss with nulls data. this way
     we won't make calls for them + we will have count=0 and will be able to hide it when empty options.
     so if we return datacount for each + we return >1 result if item is nullable until we hit the end or find non-nullable param WITH datacount>0!
     */

    public function getNomType1(Request $params){
        $nomtype = new NomType();
        $em = $this->getDoctrine()->getManager();
        $result = [];

        $checkBaseNom = $params->get('check');
        $cdata = $params->get('data');

        $criteries = [];

        if(isset($cdata['parentNameKey1']))
            $criteries['parentNameKey1'] = $cdata['parentNameKey1'];
        if(isset($cdata['parentId']))
            $criteries['parentNameKey1'] = $cdata['parentNameKey1'];
        if (isset($cdata['nameKey']))
            $criteries['nameKey'] = $cdata['nameKey'];
        if (isset($cdata['id']))
            $criteries['id'] = $cdata['id'];

        if (isset($cdata['nameKey']))
            $criteries['nameKey'] = $cdata['nameKey'];


        if (sizeof($criteries) < 1 && null === $checkBaseNom)
            throw new \Exception("Error. No fetch criteries set.", 1);


        $loadCheckCrit = [];

        $baseNomCount = null;
        if($checkBaseNom){
            $baseNomCount =  $em->getRepository(BaseNoms::class)->count($checkBaseNom);
            //print_r($checkBaseNom);exit;
            $result['baseNomCount'] = $baseNomCount;
            if (isset($checkBaseNom['parent']))
                $loadCheckCrit['parent_id'] = $checkBaseNom['parent'];
        }

        /*
        if($checkBaseNom&&empty($baseNoms)){
            return new JsonResponse(0);
        }
        */
        //print_r($criteries);exit;
        $ntList =  $em->getRepository(NomType::class)->findBy($criteries);
        $nomtypes = [];
        foreach($ntList as $nomtype) {
            //$child = $this->get('base_repo')->nomTypeList(['criteries'=>['parentNameKey1'=>$nomtype->getNameKey()]]);
            //$child = !empty($child)?$child[0]->getId():0;
            $extra = $nomtype->getExtraField();
            $nparent = $nomtype->getParent();
            $loadCheckCrit1 = array_merge(['type'=>$nomtype->getId()],$loadCheckCrit);
            //print_r($loadCheckCrit1);exit();
            $r = [
                'type' => $nomtype->getId(),
                'id' => str_replace('.', '',$nomtype->getId()),
                'parent' => (null!==$nparent?$nparent->getId():null),
                'label' => $nomtype->getName(),
                'length' => 1,
                'extraFields' => $extra,
                'nullable' => (in_array('nullable', $extra)?true:false),
                'baseNomCount' => $em->getRepository(BaseNoms::class)->count($loadCheckCrit1),
                'data' => null,
            ];
            if (sizeof($loadCheckCrit)>0 && $r['baseNomCount'] > 0) {
                $r2 = null;
                $found = $em->getRepository(BaseNoms::class)->findBy($loadCheckCrit1, ['order'=>'ASC']);
                if (sizeof($found)>0) {
                    $r2 = [ 'count' => sizeof($found) ];
                    foreach ($found as $obj) {
                        $type = $obj->getType()->getId();
                        if (!isset($r2[$type]))
                            $r2[$type] = [];

                        $r2[$type][] = [
                            'id' => $obj->getId(),
                            'value' => $obj->getName(),
                            'type' => $type,
                            'parent' => $obj->getParent()->getId(),
                            'extra' => $obj->getExtra(),
                        ];
                    }
                }
                $r['data'] = $r2;
            }
            $nomtypes[] = $r;
            //echo "D: ".$nomtype->getNameKey() . ' -> ' . $nomtype->getParentNameKey() . "\n";
        }
        $result['data'] = $nomtypes;
        return new JsonResponse($result);
    }


    /**
     * @Route("/adm/nom_type/get-nom-tree/", name="getNomTree")
     */
    public function actionNomTree(Request $request) {
        $r = $this->getDoctrine()->getRepository(NomType::class);
        $ltype = $request->get('ltype');

        $startIds = null;
        if ($request->get('startIds')) {
            $loadParents = $request->get('startIds');
            $id = $loadParents[0]['parent_id'];
        } else {
            $id = $request->get('id');
        }
        $loadParent=null;
        if (!preg_match('/^[\dA-Za-z\.\-\_]+$/', $loadParent))
            $loadParent = false;
        $opened=null;
        $loadParent = str_replace($this->dotSeparator, '.',$loadParent);
        $id = str_replace($this->dotSeparator, '.',$id);
        if ($loadParent) {
            $opened = $r->getParentTreeIds($loadParent);
            //print_r($opened);exit;
        }
        //TODO: make id possible to use as array of ids (foreach and merge result...)
        $data = $r->getTree($id, $opened, ['parent' => $loadParent, 'type' => $ltype, 'dotSeparator'=>$this->dotSeparator]);
        return new JsonResponse($data);
    }

}
