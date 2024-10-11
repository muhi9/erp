<?php

namespace UsersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use UsersBundle\Form\UserForm;
use UsersBundle\Entity\UserPersonalInfo;
use UsersBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * @Route("adm/user")
 */
class UserController extends Controller
{


    use \BaseBundle\Controller\DataGridControllerTrait;
    use \BaseBundle\Controller\FormValidationControllerTrait;

    /**
     * @Route("/", name="user")
     */
    public function indexAction(Request $request) {
        if (!$this->container->get('user.permissions')->isAdmin()) {
            return $this->redirectToRoute('profile');
        }
        return $this->render('UsersBundle:Users:index.html.twig');
    }


    /**
    * @Route("/edit/{id}", name="user_edit")
    */
    public function editAction(Request $request, $id = null){
        $em = $this->getDoctrine()->getManager();

        if ($this->container->get('user.permissions')->isAdmin()&& $id) {
            $user = $em->getRepository('UsersBundle:User')->find($id);
        } else {
            $user = $em->getRepository('UsersBundle:User')->find($this->getUser());
        }

        if (empty($user)) {
            return $this->redirectToRoute('user');
        }
        $form = $this->createForm(\UsersBundle\Form\UserFOSForm::class, $user,['current_user' => $this->getUser()]);
        $form->handleRequest($request);

        $notValid = $this->xhrValidateForm($form);
        if (isset($notValid['formErrors']) || $request->get('validationRequest') == 'only') {
            return new JsonResponse($notValid);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $userManager = $this->get('fos_user.user_manager');
            $userManager->updateUser($user);

            return $this->redirectToRoute('user');
        }

        return $this->render('@Users/Users/form.html.twig', [
            'form' => $form->createView(),
            'title' => 'users.form.edit',
            'user' => $user,
        ]);
    }




    /**
     * @Route("/autocomplete/{query}", name="user_autocomplete")
     */
    public function autocompleteAction(Request $request, $query) {
        if ($request->isXmlHttpRequest()) {
            $query = str_replace('_|_', '/', $query);
            $result = [];

            $constrains = [
                'name' => $query,
            ];
            if (($temp = $request->get('role'))!=NULL) {
                $constrains['role'] = $temp;
            }
            if (($temp = $request->get('withProfile'))!=NULL) {
                $constrains['withProfile'] = !!$temp;
            }

            $temp = $this->getDoctrine()->getManager()->getRepository(User::class)->findUser($constrains);
            //echo 's: ' . sizeof($temp);
            foreach ($temp as $tmp) {
                $result[] = [
                    'id' => $tmp->getId(),
                    'value' => $tmp->getFullName(),
                ];
            }
            return new JsonResponse($result);
        }
    }

    /**
     * @Route("/list", name="user_list")
     */
    public function listAction(Request $request) {
        
        if (!$this->container->get('user.permissions')->isAdmin()) {
            return $this->redirectToRoute('profile');
        }
        $searchCustom[] = [
                        'field' => 'username',
                        'where' => ['type'=> 'or', 'clause' => '{{entityAlias}}.username LIKE :usernameValue', 'valSetter' => ':usernameValue', 'slike' => '{{val}}%']
                        ];

        foreach (['info'] as $field) {
            $alias = 'cj_'.$field;
            $searchCustom[] = [
                'field' => $field,
                'join' => ['type'=>'left', 'selField'=> 'firstName', 'alias' => $alias],
                'where' => ['type' => 'or', 'clause' => '('.$alias.'.firstName LIKE :'.$alias.'Val or '.$alias.'.lastName LIKE :'.$alias.'Val)', 'valSetter' => ':'.$alias.'Val', 'slike' => '{{val}}%']
            ];
        }
        $table = $this->dataTable($request, User::class, [
            'route' => 'user_list',
            'action_links' => ['edit'=>'user_edit'],
            'customSearch' => ['name'=>$searchCustom],
        ], [
            'id' => [
                'title'     => 'ID',
                'orderable' => true,
            ],
            'username' => [
                'title'     => 'Портребителско име',
                'search'    => true,
                'orderable' => true,
            ],
            'first name' => [
                'title'     => 'Име',
                'getter'    => function($entity, $field, $conf) {
                    return $entity->getInfo() ? $entity->getInfo()->getFirstName() : '';
                }

            ],
            'last name' => [
                'title'     => 'Фамилия',
                'getter'    => function($entity, $field, $conf) {
                    return $entity->getInfo() ? $entity->getInfo()->getLastName() : '';
                }
            ],
            'roles' => [
                'title'     => 'Права',
                'search'    => true,
                'search_type' => 'match',
                'orderable' => true,
                'getter'    => function($entity, $field, $conf) {
                    return implode(', ', $entity->getRoles());
                }
            ],
        ]);

        return is_array($table) ? $this->render('UsersBundle:Users:list.html.twig', ['table'=>$table]) : $table;
    }
}
