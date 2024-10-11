<?php

namespace UsersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use UsersBundle\Form\UserForm;
use UsersBundle\Entity\UserPersonalInfo;
use UsersBundle\Entity\UserIDDocument;
use UsersBundle\Entity\User;
use FlightBundle\Entity\Flight;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use BaseBundle\Entity\BaseNoms;

/**
 * @Route("/profile")
 */
class ProfileController extends Controller
{

    use \BaseBundle\Controller\DataGridControllerTrait;
    use \BaseBundle\Controller\FormValidationControllerTrait;

    /**
     * @Route("/", name="profile")
     */
    public function indexAction(Request $request)
    {
        if (!$this->container->get('user.permissions')->isAdmin()) {
            if(!empty($this->getUser()->getInfo())){
                return $this->redirectToRoute('profile_edit',['id'=>$this->getUser()->getInfo()->getId()]);
            }else{
                return $this->redirectToRoute('user_edit',['id'=>$this->getUser()->getId()]);
            }
        }

        $data['title'] = 'profile.list';
        return $this->render('UsersBundle:Profile:index.html.twig', $data);
    }

    /**
     * @Route("/add", name="profile_add")
     */
    public function addAction(Request $request)
    {
        if (!$this->container->get('user.permissions')->isAdmin()) {
            if(!empty($this->getUser()->getInfo())){
                return $this->redirectToRoute('profile_edit',['id'=>$this->getUser()->getInfo()->getId()]);
            }else{
                return $this->redirectToRoute('user_edit',['id'=>$this->getUser()->getId()]);
            }
        }

        $title ='profile.add';
        return $this->form($request, $title, null);
    }

    /**
     * @Route("/edit/{id}", name="profile_edit")
     */
    public function editAction(Request $request, $id = null)
    {
        $title ='profile.edit';

        $em = $this->getDoctrine()->getManager();
        $info = $em->getRepository('UsersBundle:UserPersonalInfo')->find($id);
        /*
        get_class($info->getUser());
        empty getUser() -> UsersBundle\Controller\ProfileController
        own user -> UsersBundle\Entity\User
        another user -> Proxies\__CG__\UsersBundle\Entity\User
        */

        if (!empty($info)) {
            if (!$this->container->get('user.permissions')->isAdmin()) {
                if ($info->getUser()!=$this->getUser()) {
                    return $this->redirectToRoute('profile');
                }
            }
        }

        return $this->form($request, $title, $info);
    }

    /**
     * @Route("/view/{id}", name="profile_view")
     */
    public function viewAction(Request $request, UserPersonalInfo $id)
    {
        if ($id->getUser()==$this->getUser()) {
            return $this->redirectToRoute('profile_edit',['id'=>$id->getId()]);
        }else{
            if (!$this->container->get('user.permissions')->accessLevel(['admin','operator','instructor'])) {
                return $this->redirectToRoute('profile');
            }else{
                return $this->redirectToRoute('profile_edit',['id'=>$id->getId()]);
            }
        }
        $title ='profile.view';

        $em = $this->getDoctrine()->getManager();
        $data = [];
        $form = $this->createForm(UserForm::class, $id, ['current_user' => $this->getUser(), 'disabled'=>true, 'attr'=>['readonly'=>true]]);
        $data['form'] = $form->createView();
        $data['title'] = 'profile.view';

        return $this->render('UsersBundle:Profile:form.html.twig', $data);
    }

    /**
     * @Route("/delete/{id}", name="profile_delete")
     */
    public function deleteAction(Request $request, UserPersonalInfo $id)
    {
        if (!$id) {
            throw $this->createNotFoundException('Не е намерен резултат!');
        }

        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($id);
        $em->flush();
        return $this->redirectToRoute('profile');
    }

    /**
     * @Route("/download/{id}", defaults={"id"=null}, name="profile_document")
    */
    public function checkFileDownloadRights(Request $request, UserIDDocument $id) {
        return $this->download($id, 'profile');
    }

    /**
     * @Route("/list/{defWhere}", name="profile_list" )
     */
    public function listAction(Request $request, $defWhere = null){
        $searchCustom = [];
        $date_format = $this->getParameter('i18n');
       
        $searchPositions =[];
        

        $searchStatus = [
            0 => 'Inactive',
            1 => 'Active',
        ];
        //dump($searchPersonTypes);exit;
        $table = $this->dataTable($request, UserPersonalInfo::class, [
            'route' => 'profile_list',
            'action_links' => ['edit'=>'profile_edit', 'view'=>'profile_edit', 'delete'=>'profile_delete'],
            //'search'=>['firstName', 'lastName', 'companyName', 'nickname'],
            'customSearch' => $searchCustom,
            'order' =>['col'=>1,'sort'=>'ASC'],
            'columns_search'=>true,
        ], [
            'id' => [
                'title'     => 'ID',
                'orderable' => true,
            ],
            'firstName'=> [
                'title'=> 'Names',
                'getter' => function($entity, $field, $conf) {
                    return $entity->getFullName($official = true);
                },
                'orderable' => true,
                'search'=>['type'=>'input','value'=>null],
                'search_type'=>'orLike',
                'custom_orderKey'=>"q.firstName, q.lastName",
            ],
            /*'positions' => [
                'orderable' => true,
                'search'=>['type'=>'select','value'=>$searchPositions],
                'search_type'=>'eq',
            ],*/
            
            
            'dob' => [
                'title'     => 'Birthday (Age)',
                'orderable' => true,
                'getter'    => function($entity, $field, $conf) use ($date_format){
                    $result = '';
                    if($entity->getDob() != NULL) {
                        $age =  date_diff($entity->getDob(), new \DateTime());
                        $result = $entity->getDob()->format($date_format['date_format_s']).' ('.$age->y.')';    
                    }
                    return $result;
                    
                },
                'search'=>['type'=>'date','value'=>null],
                'searchDataTransformer' => function($val, $col, $options) {
                    $timeVal = strtotime($val);
                    return ($timeVal?date('Y-m-d', $timeVal): $val);
                },
                'search_type'=>'eq',
            ],
            
            
            'disabled' => [
                'title' => 'Status',
                'orderable' => true,
                'title'     => 'Status',
                'search'    => ['type'=>'select','value'=>['off'=>'Active', 'on'=>'Disabled']],
                'getter'    => function($entity, $field, $conf) {
                    return (!empty($entity->getDisabled()) && $entity->getDisabled() == 1)
                        ? '<span class="k-badge  k-badge--danger k-badge--inline k-badge--pill">Not Active</span>'
                        : '<span class="k-badge  k-badge--success k-badge--inline k-badge--pill">Active</span>'
                    ;
                }
            ]
            /*
            'mail' => [
                'title'     => 'Email',
               // 'orderable' => true,
                'getter'    => function($entity, $field, $conf) use ($date_format){
                    $result = '';
                    if($entity->getMail() != NULL) {
                        $cnt = 0;
                        foreach($entity->getMail() as $mail){
                            if ($cnt > 2) {
                                $result .= '...';
                                break;
                            }
                            if(!empty($mail->getInfo1())){
                                $result .= $mail->getContactType()->getName() . ': '. '<a href="mailto:'.$mail->getInfo1().'">'.$mail->getInfo1().'</a><br>';
                            }
                                //break;
                            $cnt++;
                        }
                    }
                    return $result;
                    
                },
                'search'=>['type'=>'input','value'=>null],
                'search_type'=>'slike',

            ],
            */

        ]);

        return is_array($table) ? $this->render('UsersBundle:Profile:list.html.twig', ['table'=>$table]) : $table;
    }


    public function form(Request $request, $title='',UserPersonalInfo $id = null){

        
        $data = $user_type=[];
        $em = $this->getDoctrine()->getManager();
        $id = $id ?: new UserPersonalInfo();
        $hasVals = false;
        
        
        
        $form = $this->createForm(UserForm::class, $id,['current_user' => $this->getUser()]);
        $form->handleRequest($request);
        //dump($form->getData());exit;
        $notValid = $this->xhrValidateForm($form);
        if (isset($notValid['formErrors']) || $request->get('validationRequest') == 'only') {
            return new JsonResponse($notValid);
        }

        if ($form->isSubmitted()) {
            $em->persist($id);
            $em->flush();
            return $this->redirectToRoute('profile');
        }

        $data['form'] = $form->createView();
        $data['title'] = $title;

        return $this->render('UsersBundle:Profile:form.html.twig',$data);
    }


    /**
     * @Route("/autocomplete/{query}", name="profile_autocomplete")
     */
    public function autocompleteAction(Request $request, $query) {
        if ($request->isXmlHttpRequest()) {
            $query = str_replace('_|_', '/', $query);
            $result = [];
            $constrains = [
                'name' => $query,
                'disabled' => false,
            ];
           
            if (($temp = $request->get('role'))!=NULL) {
                $constrains['role'] = explode(',', $temp);
            }
            if (($temp = $request->get('position'))!=NULL) {
                $bnomRole = $this->getDoctrine()->getManager()->getRepository(\BaseBundle\Entity\BaseNoms::class)->find($temp);
                if(isset($bnomRole->getExtraArray()['role'])
                    && !empty($bnomRole->getExtraArray()['role'])) {

                    $constrains['role'] = [$bnomRole->getExtraArray()['role']];
                }
                
                
            }


            $requestRoles = !!$request->get('requestRoles') ? $constrains['role'] : false;
            $temp = $this->getDoctrine()->getManager()->getRepository(UserPersonalInfo::class)->findUser($constrains);
            //dump($temp);
            //    die();
            //echo 's: ' . sizeof($temp);
            foreach ($temp as $k=> $tmp) {
                $val_label = $tmp->getFullName();
                $result[$k] = [
                    'id' => $tmp->getId(),
                    'value' => $val_label,
                ];
                if($request->get('extra_label') !== NULL) {
                    $getter = 'get'.ucfirst($request->get('extra_label'));
                    $extra_label = $tmp->$getter() !==NULL?$tmp->$getter():$tmp->getFullName();
                    $result[$k]['extra_label'] = $extra_label;
                    
                }
                
                if ($requestRoles) {
                    $result[$k]['roles'] = [];
                    foreach ($tmp->getPersonSubType() as $subType) {
                        if (in_array($subType->getBnomKey(), $requestRoles)) {
                            $result[$k]['roles'][] = $subType->getBnomKey();
                        }
                    }
                }
                
                
                if ($request->get('getlink')!==NULL) {
                    $result[$k]['link'] = '<a href="'.$this->generateUrl('profile_edit', ['id'=>$tmp->getId()]).'" target="_blank">'.$val_label.'</a>';
                }
            }
            return new JsonResponse($result);
        }
    }



    /**
     * @Route("/autocomplete_create/{query}", name="profile_autocomplete_create")
     */
    public function autocompleteCreateAction(Request $request, $query) {
        if ($request->isXmlHttpRequest()) {
            $result = [];
            $em = $this->getDoctrine()->getManager();

            $profile = new UserPersonalInfo();

            $query = preg_split('/\s+/', trim($query));
            $search = [];
            if ($query) {
                $profile->setFirstName(array_shift($query));
                $search['firstName'] = $profile->getFirstName();
            } else {
                return new JsonResponse(null);
            }
            if (count($query)>1) {
                $profile->setMiddleName(array_shift($query));
                $search['middleName'] = $profile->getMiddleName();
            }
            if ($query) {
                $profile->setLastName(implode(' ', $query));
                $search['lastName'] = $profile->getLastName();
            }
            $duplicates = $em->getRepository(UserPersonalInfo::class)->findBy($search);
            foreach ($duplicates as $duplicate) {
                if ($duplicate->getPersonType()->getBnomKey()=='person' && count($duplicate->getPersonSubType())==1 && $duplicate->getPersonSubType()[0]->getBnomKey()=='ROLE_PASSENGER') {
                    $profile = $duplicate;
                    break;
                }
            }

            if (!$profile->getId()) {
                $bnomRepository = $em->getRepository(\BaseBundle\Entity\BaseNoms::class);
                $profile->setPersonType($bnomRepository->findOneBy(['type'=>'user.type', 'bnomKey'=>'person']));
                $profile->addPersonSubType($bnomRepository->findOneBy(['type'=>'user.sub_type', 'bnomKey'=>'ROLE_PASSENGER']));

                $em->persist($profile);
                $em->flush();
            }
            return new JsonResponse(['id'=>$profile->getId(), 'value'=>$profile->getFullName()]);
        }
    }

    /**
     * @Route("/lastflight/", name="profile_last_flight")
     */
    public function lastFlight(Request $request) {
        $result = [];

        if ($request->isXmlHttpRequest()) {
            if (($user = $request->get('id')) !=NULL ) {
            
                $lastFlight = $this->getDoctrine()->getManager()->getRepository(Flight::class)->userLastFlight($user);
                
                if($lastFlight) {
                    $result['lastFlight'] = $lastFlight->getFlightFrom()->format($this->getParameter('i18n')['date_format_s']);
                }
                
                $nextExercise =  $this->getDoctrine()->getManager()->getRepository(Flight::class)->userLastFlight($user, ['hasNextExercise'=>true]);
                if($nextExercise){
                    $result['nextExercise'] = $nextExercise->getNextExercise();
                }
            }

        }
        return new JsonResponse($result);
    }



}
