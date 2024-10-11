<?php

namespace FileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

use FileBundle\Entity\File as FileEntity;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use UsersBundle\Entity\UserPersonalInfo;


/**
 * @Route("/file")
 */
class FileController extends Controller
{

    /**
     * @Route("/file")
     */
    public function indexAction()
    {
        return $this->render('FileBundle:Default:index.html.twig');
    }


    /**
     * @Route("/download/{file}", name="file_bundle_get_file")
     */
    public function downloadAction(Request $request, $file)
    {
        return $this->container->get('files')->fileResponse($file);
/*
        // handle 404
        $file = $this->find404File($file);
        if ($file instanceof Response) {
            return $file;
        }
        $access = $this->checkUserAccess($file);
        if (true !== $access) return $access;
        return $this->file($file->getPath(). DIRECTORY_SEPARATOR . $file->getDiskName(), $file->getName(), ResponseHeaderBag::DISPOSITION_INLINE);
*/
    }


    /**
     * @Route("/avatar/{id}", name="file_bundle_get_profile_avatar")
     */
    public function profileAvatarAction(Request $request, UserPersonalInfo $id = null) {
        return $this->container->get('files')->fileResponse($id ?: new UserPersonalInfo());
/*
        if ($id && $id->getAvatar()) {
            $file = $id->getAvatar();
            return $this->file($file->getPath(). DIRECTORY_SEPARATOR . $file->getDiskName(), $file->getName(), ResponseHeaderBag::DISPOSITION_INLINE);
        } else {
            $path = $this->get('kernel')->getRootDir() . '/../web/';
            $file = $this->getParameter('empty_avatar_url');
            return $this->file($path.$file, basename($path.$file), ResponseHeaderBag::DISPOSITION_INLINE);
        }
*/
    }


    /**
     * @Route("/delete/{file}", name="file_bundle_del_file")
     */
    public function deleteAction(Request $request, $file)
    {
        // handle 404
        $file = $this->find404File($file);
        if ($file instanceof Response) {
            return $file;
        }
        $access = $this->checkUserAccess($file);
        if (true !== $access) return $access;
        $resp = [];
        $fileId = $file->getId();
        $em = $this->getDoctrine()->getManager();

        try {
            $fileClass = $file->getEntityClass();
            if (strstr($fileClass, '::many::')) {
                // collection files in entity
                $fileVar = substr($fileClass,strrpos($fileClass,'::')+2);
                $fileVarGetter = 'get'.ucfirst($fileVar);
                $fileVarRemover = rtrim('remove'.ucfirst($fileVar),'s');
                $fileClass = substr($fileClass,0,strpos($fileClass,'::'));
                //echo 'd: '.$fileClass.'---'.$fileVar;exit;
                $entityId = $request->get('eid');
                $fileRelation = $em->getRepository($fileClass)->find($entityId);
                //We do not remove relation, because we delete the file record, because collection is reset and deependednt deleted
                //$fileRelation->$fileVarRemover($file);
            } else {
                // single file in entity
                $fileVar = substr($fileClass,strpos($fileClass,'::')+2);
                $fileVarSetter = 'set'.ucfirst($fileVar);
                $fileClass = substr($fileClass,0,strpos($fileClass,'::'));
                //echo 'd: '.$fileClass.'---'.$fileVar.'--'.$file->getId();exit;
                $fileRelation = $em->getRepository($fileClass)->findOneBy([$fileVar=>$file->getId()]);
                $entityId = $request->get('eid');
                if ($file->getEntityId() == -1 && $entityId>0) {
                    $file->setEntityId($entityId);
                }
                if(!method_exists($fileRelation, $fileVarSetter)) {
                    throw new \Exception("Error getting setter of parent: $fileClass->$fileVarSetter()", 1026);
                    //throw new \Exception("Error getting setter of parent: $fileClass->$fileVarSetter()", 1026);
                }
                $fileRelation->$fileVarSetter(null);
                $em->persist($fileRelation);
            }

            $file->setDeletedBy($this->getUser());
            $file->setDeletedAt(new \Datetime());
            $em->persist($file);
            //$em->remove($file);

            $em->flush();
            $resp['deleted'] = $fileId;
            $resp['success'] = true;
            $resp['msg'] = 'Successfully deleted file.';
        } catch (\Exception $e) {
           unset($resp['success'], $resp['deleted']);
           $resp['error'] = $this->get('translator')->trans('exception.onDelete').$e->getMessage();
        }

        return new JsonResponse($resp);
    }

    // redirect to user_license list when user is not admin and userId requested is not it's own
    private function checkUserAccess(FileEntity $file) {
        if(in_array('ROLE_ADMIN', $this->getUser()->getRoles()))
            return true;
        if(in_array('ROLE_OPERATOR', $this->getUser()->getRoles()))
            return true;
        if(in_array('ROLE_INSTRUCTOR', $this->getUser()->getRoles()))
            return true;
        if ($file->getCreatedBy()->getId() == $this->getUser()->getId())
            return true;


        return new JsonResponse(['errorMsg' => 'Flu parameter error!']);
    }

    private function find404File($file) {
        $em = $this->getDoctrine()->getManager();
        $fileRepo = $em->getRepository(FileEntity::class);
        $file = $fileRepo->find($file);
        if (null == $file) {
            // file not found
            return new Response('File not found', Response::HTTP_NOT_FOUND);
        }
        return $file;
    }

}
