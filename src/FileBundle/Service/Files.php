<?php
namespace FileBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use FileBundle\Entity\File;
use UsersBundle\Entity\UserPersonalInfo;
use UsersBundle\Entity\User;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Response;


class Files {
    private $entityManager;
    private $tokenStorage;
    private $router;
    private $root;
    private $emptyAvatar;


    public function __construct(EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage, $router, $root, $emptyAvatar) {
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
        $this->router = $router;
        $this->root = $root;
        $this->emptyAvatar = $emptyAvatar;
    }


    public function checkUserAccess(File $file) {
        $user = $this->tokenStorage->getToken()->getUser();
        return $user
            && (
                !!array_intersect(['ROLE_ADMIN', 'ROLE_OPERATOR', 'ROLE_INSTRUCTOR'], $user->getRoles())
                || $file->getCreatedBy()->getId() == $user->getId()
            );
    }


    public function currentUserAvatar() {
        return $this->getURL($this->tokenStorage->getToken()->getUser(), false, 'avatar') ?: $this->emptyAvatar;
    }


    public function getURL($object, $checkRights = true, $UserPersonalInfoType = 'avatar') {
        $file = $this->getFile($object, $checkRights, $UserPersonalInfoType);
        return $file ? $this->router->generate('file_bundle_get_file', ['file'=>$file->getId()]) : null;
    }



    public function getFile($object, $checkRights = true, $UserPersonalInfoType = 'avatar') {
        $result = null;
        if (is_numeric($object)) {
            $result = $this->entityManager->getRepository(File::class)->find($object);
        } elseif ($object instanceof UserPersonalInfo) {
            $result = $object->getImage($UserPersonalInfoType);
        } elseif ($object instanceof User) {
            $result = null;
            //$result = $object->getInfo()->getAvatar();
        } elseif ($object instanceof File) {
            $result = $object;
        }
        if ($result && $checkRights && !$this->checkUserAccess($result)) {
            $result = null;
        }
        return $result;
    }



    public function fileResponse($object, $checkRights = true, $UserPersonalInfoType = 'avatar') {
        $temp = $this->get($object, $checkRights, $UserPersonalInfoType);
        if ($temp['filePath'] && file_exists($temp['filePath'])) {
            $result = new BinaryFileResponse($temp['filePath']);
            $result->setContentDisposition(ResponseHeaderBag::DISPOSITION_INLINE, $temp['fileName']);
        } else {
            $result = new Response('File not found', Response::HTTP_NOT_FOUND);
        }
        return $result;
    }



    public function embedFile($object, $checkRights = true, $UserPersonalInfoType = 'avatar') {
        $temp = $this->get($object, $checkRights, $UserPersonalInfoType);
        return $this->base64($temp['filePath']);
    }



    public function embedImage($object, $checkRights = true, $UserPersonalInfoType = 'avatar') {
        $temp = $this->get($object, $checkRights, $UserPersonalInfoType);
        $result = ['width'=>null, 'height'=>null, 'data'=>null];
        if ($temp['filePath']) {
            $mime = mime_content_type($temp['filePath']);
            $opener = str_replace('/', 'createfrom', $mime); // image/png => imagecreatefrompng; text/vnd.in3d.3dml => textcreatefromvnd.in3d.3dml
            if (!file_exists($temp['filePath']))
                return new Response('File not found', Response::HTTP_NOT_FOUND);
            if (function_exists($opener)) {
                if ($img = $opener($temp['filePath'])) {
                    $result = [
                        'width' => imagesx($img),
                        'height' => imagesy($img),
                        'data' => $this->embedFile($object, false, $UserPersonalInfoType),
                    ];
                    imagedestroy($img);
                }
            }
        }
        return $result;
    }



    public function localImage($object, $checkRights = true, $UserPersonalInfoType = 'avatar') {
        $temp = $this->get($object, $checkRights, $UserPersonalInfoType);
        if ($temp['filePath']) {
            $temp['filePath'] = $temp['filePath'];
        }
        return $temp['filePath'];
    }



    private function get($object, $checkRights, $UserPersonalInfoType) {
        $isUserImage = $object instanceof UserPersonalInfo || $object instanceof User;
        $file = $this->getFile($object, !$isUserImage && $checkRights, $UserPersonalInfoType);
        $filePath = $fileName = null;
        if (!$file && $isUserImage) {
            $filePath = $this->root . DIRECTORY_SEPARATOR . $this->emptyAvatar;
            $fileName = basename($filePath);
        } else if ($file) {
            $filePath = $file->getPath() . DIRECTORY_SEPARATOR . $file->getDiskName();
            $fileName = $file->getName();
        }
        return ['filePath'=>$filePath, 'fileName'=>$fileName];
    }



    private function base64($filePath) {
        if (!file_exists($filePath))
            return new Response('File not found', Response::HTTP_NOT_FOUND);

        $mime = $filePath ? mime_content_type($filePath) : null;
        return  $mime ? 'data:'.$mime.';base64,'.base64_encode(file_get_contents($filePath)) : null;
    }

}
