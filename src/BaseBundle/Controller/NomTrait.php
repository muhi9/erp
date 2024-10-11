<?php

namespace BaseBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

use BaseBundle\Entity\BaseNoms;

use BaseBundle\Entity\BaseNomsExtra;
use BaseBundle\Entity\NomType;
use BaseBundle\Form\BaseNomsType as BaseNomsForm;


trait NomTrait
{
    use \BaseBundle\Controller\DataGridControllerTrait;
    use \BaseBundle\Controller\FormValidationControllerTrait;


    public function baseIndex(Request $request) {
        $isAdmin = $this->container->get('user.permissions')->isAdmin();

        $options = [
            'route' => 'basenom_list',
            'action_links' => ['edit'=>'basenom_edit'],
//            'order' => [ 'id' => 'ASC', 'name' => 'ASC'],
            'order' => ['col'=>1, 'sort'=>'ASC'],
            'defWhere' => [],
//            'search' => 'type',
        ];

        if ($request->get('lpid')) {
            $options['defWhere']['parent'] = $request->get('lpid');
//            $data['lpid'] = $request->get('lpid');
        } else if ($request->get('startIds')) {
            $options['defWhere']['parent_id'] = $request->get('startIds')[0]['parent_id'];
        }
        if ($request->get('ltype')) {
            $lpid = $this->getDoctrine()->getRepository(BaseNoms::class)->findOneBy(['type' => $request->get('ltype')]);
            /*
            if ($lpid) {
                $data['lpid'] = $lpid->getId();
            }
            */
            $options['defWhere']['type'] = $request->get('ltype');
        }

        if ($isAdmin) {
            $options['action_links']['status_link'] = function() {
                return '<a href="javascript:void(0);" onclick="toggleStatus(\'{{{pk_value}}}\', \''.$this->generateUrl('nom_status', ['pk_field' => '{{{pk_value}}}']).'\');" class="btn btn-sm btn-clean btn-icon btn-icon-md confirmation"  title="Toggle status"> <i class="la la-star-half-o"></i></a>';
            };
        }

        $nomTypesArray = [];

        $table = $this->dataTable($request, BaseNoms::class, $options, [
            'id' => [
                'title'     => 'ID',
                'orderable' => true,
            ],
            'name' => [
                'search' => ['type'=>'input'],
            ],
            'type' => [
                'search' => ['type'=>'select','value'=>$nomTypesArray]
            ],
            'parent' => [
                'title' => 'Parent',
                'getter'    => function($entity, $field, $conf) {
                    return $entity->getParent() ? $entity->getParent() : '';
                }

            ],
            'order' => [
                'title' => 'Order',
                'reorder' => 'basenom_reorder',
                'getter' => function($entity, $field, $conf) {
                    return $entity->getOrder() ?: 0;
                }
            ],
        ]);

        if($this->get('session')->get('errors')!=null){
            $data['errors'] = $this->get('session')->get('errors');
            $this->get('session')->clear('errors');
        }

        if (is_array($table)) {
            $nomtree_filters = [];//'test'=>1];
            if ($request->get('startIds')) {
                $loadParents = $request->get('startIds');
                $nomtree_filters = ['startIds' => $loadParents];
            }
        }

        return is_array($table) ? $this->render('@Base/BaseNom/index.html.twig', ['table'=>$table, 'nomtree_filters'=>$nomtree_filters]) : $table;
/*



            $em = $this->getDoctrine()->getManager();
            $superAdmin = false;
            if(in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
                $superAdmin = true;
            }
            $tableData = [];
            $data = [];
            $searchJoin = ['extra'=>'baseValue'];
            $findOpts = [
                'order' => [ 'id' => 'ASC', 'name' => 'ASC'],
                'columns' => ['id','name','type','extra'],
                'search'=>'type',
                'searchJoin'=>$searchJoin,
            ];
            if ($request->get('lpid')) {
                $findOpts['defWhere']['parent'] = $request->get('lpid');
                $data['lpid'] = $request->get('lpid');
                //print_r($tree);exit;
            } else if ($request->get('startIds')) {
                $findOpts['defWhere']['parent_id'] = $request->get('startIds')[0]['parent_id'];
                //$data['lpid'] = $findOpts['defWhere']['id'];
                //print_r($tree);exit;
                //echo $findOpts['defWhere']['parent_id'];exit;
            }
            if ($request->get('ltype')) {
                //$data['ltype'] = $request->get('ltype');
                // selecting ltype doesn't work properly. we loop only minimum depth in tree by default.
                // if type is not in tree (usual case) we won't get opened type.
                // thus we find first id that's of that type and we set lpid because it loads properly
                // the tree in depth.
                $lpid = $this->getDoctrine()->getRepository(BaseNoms::class)->findOneBy(['type' => $request->get('ltype')]);
                if (null !== $lpid)
                    $data['lpid'] = $lpid->getId();
                $findOpts['defWhere']['type'] = $request->get('ltype');
            }
            $gridDatas = $this->dataGridPrepareHandler($request, BaseNoms::class, $findOpts);
            $gridDatas['getdata']['criteries']['status'] = 1;
            $orderCol = isset($gridDatas['getdata']['criteries']['like']['type'])&&!empty($gridDatas['getdata']['criteries']['like']['type'])?true:false;


            if($this->get('session')->get('errors')!=null){
               $data['errors'] = $this->get('session')->get('errors');
               $this->get('session')->clear('errors');
            }

            $showData['list'] = [];
            foreach ($gridDatas['list'] as $key => $object) {
                $parent = false;
                if($object->getParent()!==null){
                      $parent = $object->getParent();//$this->getDoctrine()->getRepository(BaseNoms::class)->find($object->getParentId());
                }

                $showData['list'][$key]['id'] = $object->getId();
                $showData['list'][$key]['name'] = $object->getName();
                $showData['list'][$key]['parent'] = !empty($parent)?$parent->getName():'';
                $showData['list'][$key]['type'] = $object->getType()->getId();
                $showData['list'][$key]['status'] = $object->getStatus()?1:0;
                $extraInfo = [];
                if($object->getExtra()!=null){
                  foreach ($object->getExtra() as  $exf) {
                        $extraInfo[] =$exf->getBaseKey().':'.$exf->getBaseValue();
                    }
                }
                $showData['list'][$key]['extra'] = implode(", ", $extraInfo);
                $showData['list'][$key]['order'] = $object->getOrder()==null?'0':$object->getOrder();

            }



        $nomTypesArray = [];//$this->get('nom_type_tree')->createNomTypeTree();



        if($request->isXmlHttpRequest()) {
            foreach($showData['list'] as $row) {
                $row['actions'] = null;
                $data[] = $row;
            }

            $tableData = [
                "iTotalRecords" => $gridDatas['limit'],
                "iTotalDisplayRecords" => $gridDatas['count'],
                "sEcho" => 0,
                "sColumns" => "",
                "aaData" => $showData['list'],
            ];

            return new JsonResponse($tableData);
        } else {

            $actionLinks = [
                'edit_link' => [
                    'html' => '<a href="'
                        . $this->generateUrl('basenom_edit', array('pk_field'=> '{{{pk_value}}}'))
                        . '" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit"> <i class="la la-edit"></i></a>',
                ],
            ];
            if ($superAdmin) {
                $actionLinks['status_link'] = [
                    'html' => '<a href="javascript:void(0);" onclick="toggleStatus(\'{{{pk_value}}}\', \''.$this->generateUrl('nom_status', ['pk_field' => '{{{pk_value}}}']).'\');" class="btn btn-sm btn-clean btn-icon btn-icon-md confirmation"  title="Toggle status"> <i class="la la-star-half-o"></i></a>',
                ];
                /*
                $actionLinks['delete_link'] = [
                    'html' => '<a href="'
                        . $this->generateUrl('basenom_delete', array('pk_field'=> '{{{pk_value}}}'))
                        . '" class="btn btn-sm btn-clean btn-icon btn-icon-md confirmation"  title="Delete"> <i class="la la-remove"></i></a>',
                ];
                * /
            }


            $table = [
                'name' => BaseNoms::class,
                'order'=>['col'=>5,'sort'=>'ASC'],
                'ajax_data_url' => $this->generateUrl('basenom_list', array('slug' => 'ajax-index')),
                'action_links' => $actionLinks,
                'fnRowCallback' => 'function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) { if (aData[\'status\']==0) $(nRow).addClass(\'deleted\') }',
                'pk_field' => 'id',
                'fields' => [
                    'id'=>'id',
                    'name'=>'Name',
                    'type'=>'Type',
                    'parent'=>'Parent',
                    'extra'=>'Info',
                    'order'=>'Order',

                ],
                'searchFields' => [
                    '0'=>['type'=>null,'value'=>null],
                    '1'=>['type'=>'input','value'=>null],
                    '2'=>['type'=>'select','value'=>$nomTypesArray],
                    '3'=>['type'=>null,'value'=>null],
                    '4'=>['type'=>'input','value'=>null],
                    '5'=>['type'=>null,'value'=>null],
                    '6'=>['type'=>null,'value'=>null],
                    //'actions'=>'Action',
                ],
                'custom_column_defs' => [
                    'id' => [
                        'orderable'=> 'true',
                        'target'=> 0,

                    ],
                    'name' => [
                        'orderable'=> 'false',
                        'searchable'=> 'true',
                    ],
                    'type' => [
                        'orderable'=> 'false',
                        'searchable'=> 'true',
                    ],
                    'extra' => [
                        'orderable'=> 'false',
                        'searchable'=> 'true',
                    ],
                    'parent' => [
                        'orderable' => 'false',
                        'searchable' => 'false',
                    ],
                    'order' => [
                        'orderable'=> 'false',
                        'searchable'=> 'false',
                        'createdCell' => " function (td, cellData, rowData, row, col) {
                                $(td).attr('data-id', rowData.id);
                                $(td).attr('data-val', rowData.order);
                                $(td).addClass('orderCell');
                         }"

                    ],
                ],
                "ajaxComplete" => "function(json) {
                    setTimeout(function() {
                        if ($('.dataTables_scrollHead').find('[aria-label=\"Order\"]').find('.reordeBasenoms').length<1) {
                            var tbox=$('.dataTables_scrollHead').find('[aria-label=\"Order\"]').html() + ' <a href=\"javascript:void(0);\"  class=\"btn mar k-margin-l-5 reordeBasenoms btn-sm btn-elevate btn-brand saveOrder\" title=\"".$this->get('translator')->trans('label.reorder')."\"><span></span></a>';

                            $('.dataTables_scrollHead').find('[aria-label=\"Order\"]').html(tbox);
                        }
                    }, 500);
                    return json.aaData;
                }",
                'scrollY' => '90%',

            ];
            $data['table']= $table;
            $data['nomtree_filters'] = [];//'test'=>1];
            if ($request->get('startIds')) {
                $loadParents = $request->get('startIds');
                //print_r($loadParents);exit;
                $data['nomtree_filters'] = ['startIds' => $loadParents];
            }
            return $data;
        }
        */
    }

    public function baseLoadChild(Request $request) {
        $extra = false;
        $newData = false;
        $em = $this->getDoctrine()->getManager();
        $criteries['status'] = 1;
        if($request->isXmlHttpRequest()) {
            $param = $request->get('data');
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
            if(isset($param['criteries']['extraFields'])&&!empty($param['criteries']['extraFields'])) {
                 $extra = $param['criteries']['extraFields'];
            }
            if (isset($param['req']) && $param['req'] == 'basenomtree' )
                $newData = true;
            //print_r($criteries);exit;
            $besnomChild = $this->getDoctrine()->getRepository(BaseNoms::class)->findBy($criteries, ['order' => 'ASC']);
            $result = [];
            if ($newData) {
                $result = [ 'data' => [ 'count' => 0 ] ];
            }
            foreach ($besnomChild as $key => $value) {
                $type = $value->getType()->getId();
                $tmp = [];
                $tmp['id'] = $value->getId();
                $tmp['value'] = $value->getName();
                $tmp['type'] = $type;
                $tmp['bnomkey'] = $value->getBnomKey();
                if ($value->getParent())
                    $tmp['parent'] = $value->getParent()->getId();
                else
                    $tmp['parent'] = '';
               
                if ($newData) {
                    $result['data'][$type][] = $tmp;
                    $result['data']['count'] += 1;
                } else {
                    $result['data'][] = $tmp;
                }

            }

            return new JsonResponse($result);


        } else {
            return new JsonResponse(['error' => 'Invalid request']);
        }
    }


    public function baseReorder(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if($request->isXmlHttpRequest() && $request->isMethod('post')) {
            $data = $request->get('data');
            $status = ['status' => 'unkown'];

            try {
                foreach($data as $ord) {
                    $status[$ord['id']] = 'unkown';
                    $nom = $em->getRepository(BaseNoms::class)->findOneBy(['status' => 1, 'id' => $ord['id']]);
                    if ($nom && is_numeric($ord['order']) && $ord['order'] > -1 && $ord['order'] < 100000) {
                        $nom->setOrder($ord['order']);
                        $em->persist($nom);
                        $status[$ord['id']] = 'saved';
                    } else {
                        $status[$ord['id']] = 'invalid order number';
                    }
                }
                $em->flush();
                $status['status'] = 'saved';
            } catch (\Exception $e) {
                $status['status'] = 'error';
                $status['error'] = 'Flush error: ' . $e->getTraceAsString();
            }

            return new JsonResponse($status);

        } else {
              return new JsonResponse('pme');
        }


    }

    public function baseGetList(Request $request) {
        $criteries = $request->get('data');

        $data['criteries']['like']['name']=$criteries['val'];
        $data['criteries']['type']=$criteries['type'];

        $r = $this->getDoctrine()->getRepository(BaseNoms::class)->readList($data);
        $result = $tmp = [];

        foreach ($r as $key => $value) {

            $data = $value;
            $tmp[$data->getId()]=$data->getName();
        }

        if(!empty($tmp)){
            $result = $tmp;
        }
        return new JsonResponse($result);


    }


    public function baseStatus(BaseNoms $basenom) {
        $em = $this->getDoctrine()->getManager();
        if (null===$basenom)
            return new JsonResponse(['error'=>"Unable to find Nomenclature with id: ". $bid]);

        $basenom->setStatus($basenom->getStatus()==0?1:0);
        $em->persist($basenom);
        $em->flush();
        return new JsonResponse($basenom->getId());
    }

    public function baseNomTree1(Request $request) {
        $r = $this->getDoctrine()->getRepository(BaseNoms::class);
        $ltype = $request->get('ltype');
        $loadParent = $request->get('lpid');

        $startIds = null;
        if ($request->get('startIds')) {
            $loadParents = $request->get('startIds');
            $id = $loadParents[0]['parent_id'];
        } else {
            $id = $request->get('id');
        }
        if (!preg_match('/^[\d]+$/', $loadParent))
            $loadParent = false;
        $opened=null;
        if ($loadParent) {
            $opened = $r->getParentTreeIds($loadParent);
            //print_r($opened);exit;
        }
        //TODO: make id possible to use as array of ids (foreach and merge result...)
        $data = $r->getTree($id, $opened, ['parent' => $loadParent, 'type' => $ltype]);
        return new JsonResponse($data);
    }

    public function baseNomTree(Request $request) {
        $r = $this->getDoctrine()->getRepository(BaseNoms::class);
        $dir = $request->get('dir');
        $listId=null;
        if (!empty($dir) && preg_match('/^listId=([\d]+)\/$/', $dir, $m)) {
            $listId = $m[1];
        }
        $nomTypes = $this->getDoctrine()->getRepository(NomType::class)->getNames();
        $treeStr = "<ul class='jqueryFileTree'>";
        $list = $r->findBy(['parent' => $listId], ['type'=>'asc']);
            foreach ($list as $el) {
                $checkbox='';
                $htmlRel = 'listId='.$el->getId();
                $typeHint = '';
                if (isset($nomTypes[$el->getType()->getId()])) $typeHint = $nomTypes[$el->getType()->getId()];
                $infoHint = '<span class="d-inline-block" tabindex="0" data-toggle="tooltip" data-original-title="'.$typeHint.'"><i class="la la-info k-padding-3"></i></span>';
                $htmlName = $el->getName() . '(' . $el->getType()->getId() . ' ' . $infoHint . ')';
                $list1 = $r->findBy(['parent' => $el->getId()]);
                if (sizeof($list1) > 0) {
                    $linkPid = $el->getId();
                } else {
                    $linkPid = $el->getParent()->getId();
                }
                $links = "<a href='" . $this->generateUrl('basenom_add', ['parent' => $linkPid, 'type' => $el->getType()->getId() ]) . "' alt='Add element'><i class='la la-plus k-padding-3'></i></a>";
                if (sizeof($list1) > 0) {
                    $treeStr .= "<li class='directory collapsed'>{$checkbox}<a rel='" .$htmlRel. "/'>" . $htmlName . "</a> " . $links . "</li>";
                } else {
                    $treeStr .= "<li class='file ext_txt'>{$checkbox}<a rel='" .$htmlRel. "/'>" . $htmlName . "</a> " . $links . "</li>";
                }
            }
        $treeStr .= "</ul>
        <script>$('[data-toggle=\"tooltip\"]').tooltip(); </script>";
        return new Response($treeStr);
    }
}
