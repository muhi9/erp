<?php

namespace FileBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\File as FileConstraint;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\DependencyInjection\Container;
use FileBundle\Entity\File as FileEntity;
use Psr\Container\ContainerInterface;
use Doctrine\ORM\EntityManagerInterface;
use BaseBundle\Entity\BaseNoms;
use BaseBundle\Form\Type\BaseNomType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;


class FileInputType extends AbstractType {
    private $builder = NULL;

    private $container;
    private $em;

    private $uploadedFiles = [];

    protected $baseNomOpts = ['baseNom1'=>'bnomType1', 'baseNom2' => 'bnomType2', 'baseNom3' => 'bnomType3', 'baseNom4' => 'bnomType4'];

    private $addConstraints=false;

    public function __construct(ContainerInterface $container, EntityManagerInterface $em) {
        $this->container = $container;
        $this->em = $em;
    }



    public function buildForm(FormBuilderInterface $builder, array $options) {
        $constraints = [];
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($options) {
            // single file (no collection)
            $dataClass = $event->getForm()->getParent()->getConfig()->getDataClass();
            $dataName = $event->getForm()->getConfig()->getName();
            // we have no parent entity. we create entity + uploadfile. we can't have entity parent id now...
            $entityId = -1;
            if (null === $dataClass && preg_match('/^[\d]+$/', $dataName)) {
                // if data class is null and name is number means we are in collection.
                $dataClass = $event->getForm()->getParent()->getParent()->getConfig()->getDataClass() . '::many';
                $dataName = $event->getForm()->getParent()->getConfig()->getName();
                if (null !== $event->getForm()->getParent()->getParent()->getData()
                  && null !== $event->getForm()->getParent()->getParent()->getData()->getId())
                    $entityId = $event->getForm()->getParent()->getParent()->getData()->getId();
            } else {
                //dump($event->getForm()->getParent()->getParent()->getParent()->getData()->getId());exit;
                if (null !== $event->getForm()->getParent()->getData()
                  && null !== $event->getForm()->getParent()->getData()->getId()) {
                    // we have parent entity - we are uploading second file for this
                    $entityId = $event->getForm()->getParent()->getData()->getId();
                }
            }
            $formClass = $dataClass . '::' . $dataName;
            //dump($formClass);exit;
            //dump($event->getData());exit;
            // new file, no file record
            if (isset($event->getData()['fileName']) && null === $event->getForm()->getData()) {
                $customName = false;
                if (isset ($event->getData()['customName']) && !empty($event->getData()['customName'])) {
                    $customName = $event->getData()['customName'];
                }
                $file = $event->getData()['fileName'];
                $fileEnt = new FileEntity();
                //$fileEnt->setPath(rtrim($this->container->getParameter('upload_directory'), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR)
                $fileEnt->setEntityClass($formClass);
                $fileEnt->setEntityId($entityId);
                $fileEnt->setProjectDir($this->container->get('kernel')->getProjectDir());
                $fileEnt->setPath($this->container->getParameter('upload_directory'));

                $path = $fileEnt->getPath();
                if (!file_exists($path)) {
                    throw new \Exception("$path upload folder is missing", 1);
                } else if (!is_writable($path)) {
                    throw new \Exception("$path upload folder is ro", 1);
                }

                $mime = $file->getMimeType();
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_BASENAME);
                $extension = $file->guessClientExtension() ?: pathinfo($originalFilename, PATHINFO_EXTENSION);
                // Synchronize with BaseBundle\Traits\FileEntity::getFileName
                $newFilename = uniqid((time().'_'), true) . '.' . $extension;

                $resized_image = false;
                if (!empty($options['resize_image']) && is_callable($options['resize_image'])) {
                    $options['resize_image'] = call_user_func($options['resize_image'], $event->getData());
                }
                if (!empty($options['resize_image']) && (!empty($options['resize_image']['max_width']) || !empty($options['resize_image']['max_height']))) {
                    $dstFilename = uniqid((time().'_'), true) . '.png';
                    if ($this->resize_image($file, $path.DIRECTORY_SEPARATOR.$dstFilename, $mime, $options['resize_image']['max_width'], $options['resize_image']['max_height'], !empty($options['resize_image']['keep_ratio']))) {
                        $newFilename = $dstFilename;
                        $mime = 'image/png';
                        @unlink($file);
                        $resized_image = true;
                    }
                }

                $fileEnt->setName($originalFilename);
                $fileEnt->setDiskName($newFilename);
                $fileEnt->setMimeType($mime);
                if (!$resized_image) {
                    $file->move($path, $newFilename);
                }
                $this->uploadedFiles[] = $fileEnt;
                //$this->em->persist($fileEnt);
                //$this->em->flush();

                //$this->addConstraints=true;
                //dump('new file', $fileEnt);
            } else {
                // WARNING: we remove the element ONLY when it's not part of files collection!!!
                // if it's part of files collection (doesnt matter if above we have other collection)
                // we must not remove it because collection of files gets deleted when not passsed.
                //dump(get_class($event->getForm()->getParent()->getData()) == "Doctrine\ORM\PersistentCollection");exit;
                if (!$event->getForm()->getParent()->getData() instanceof \Doctrine\ORM\PersistentCollection
                  && $options['required'] === false) {
                    // remove self - when no file is selected field is empty
                    // but framework still tries to insert a File record (ignores empty field).
                    // and validation of required fields fails.
                    // that's why we must remove 'self' (this) field from parent form.
                    $event->getForm()->getParent()->remove($event->getForm()->getName());
                }
            }
            //end new file
        });

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) use ($options) {
            $dataClass = $dataName = null;
            if (null !== $event->getForm()->getParent()) {
                $dataClass = $event->getForm()->getParent()->getConfig()->getDataClass();
                $dataName = $event->getForm()->getConfig()->getName();
            }
            // populate new file data - only for new files
            foreach ($this->uploadedFiles as $fileEnt) {
                //dump($event->getForm()->getConfig()->getName());exit;
                if (null == $event->getData()) {
                    throw new \Exception("Data is empty. Should not happen.");
                    //$event->setData($fileEnt);
                } else {
                    //set data ONLY for proper entity file field!!!
                    //dump($event->getForm());exit;
                    if (("$dataClass::$dataName" == $fileEnt->getEntityClass()) || (empty($dataClass) && is_numeric($dataName))) {
                        $event->getData()->setEntityClass($fileEnt->getEntityClass());
                        $event->getData()->setEntityId($fileEnt->getEntityId());
                        $event->getData()->setName($fileEnt->getName());
                        $event->getData()->setDiskName($fileEnt->getDiskName());
                        $event->getData()->setMimeType($fileEnt->getMimeType());
                        $event->getData()->setProjectDir($this->container->get('kernel')->getProjectDir());
                        $event->getData()->setPath($fileEnt->getPath());
                    }

                }
            }
            //exit;
            // set custom name in name field when not empty (allow update withhout del of file)
            /*
            if($event->getForm()->has('customName') && !empty($event->getForm()->get('customName')->getViewData())) {
                $event->getData()->setName($event->getForm()->get('customName')->getViewData());
            }
            */
            //dump(sizeof($this->uploadedFiles));
        });


        $fileNameOptions = [
            'maxSize' => $options['maxSize'],
            'mime' => $options['mime'],
        ];

        /*
        TODO: fix file constraints
        $constraints[] =new FileConstraint([
            'binaryFormat'=>true,
                'maxSize' => '1M',//$options['maxSize'],
                'mimeTypes' => FileUploadType::mime($options['mime'])
            ]);
        */
        if ($options['required']) {
            $constraints[] = new NotBlank();
        }
        //dump($constraints);exit;
        /*
        $builder1 = $builder->getForm()->getConfig()->getFormFactory()->createNamedBuilder('fileName', FileUploadType::class, null, [
            'auto_initialize'=>false, // it's important!!!
                    'mapped' => false,
                    'maxSize' => $options['maxSize'],
                    'mime' => $options['mime'],
                    'constraints' => $constraints,
                ]);

        $builder1->addEventListener(\Symfony\Component\Form\FormEvents::PRE_SET_DATA, function (\Symfony\Component\Form\FormEvent $event) use ($options) {
            //echo 'shit';exit;
        });
        $builder->add($builder1);
        */
        $fileNameOptions['constraints'] = $constraints;
        $builder
        ->add('fileName', FileUploadType::class, $fileNameOptions)
        ->add('dummyField', HiddenType::class, ['data' => 'dummy data'])
        ;

        //$builder->add('image', null, ['mapped'=>false]);
        // add baseNom field/s/
        foreach ($this->baseNomOpts as $nomKey => $nomType) {
            if (isset($options[$nomKey]) && $options[$nomKey]['enabled'] === true && strlen($options[$nomKey]['nomKey']) > 3) {
                $opts = [
                        'addEmpty' => false,
                        'by_reference' => false,
                        'mapped' => true,
                        'baseNom' => $options[$nomKey]['nomKey'],
                ];
                if(isset($options[$nomKey]['label']) && !empty($options[$nomKey]['label']))
                    $opts['label'] = $options[$nomKey]['label'];

                $builder->add($nomType, BaseNomType::class, $opts);
            }
        }

        // add issuedOn, validFrom, validTo fields.
        foreach (['issuedOn', 'validFrom', 'validTo'] as $dtype) {
            $dateType = DateType::class;
            if (isset($options[$dtype]) && $options[$dtype]['enabled'] === true) {
                $opts = [
                    'by_reference' => false,
                    'mapped' => true,
                    'label' => $dtype,
                ];
                if (isset($options[$dtype]['label']) && !empty($options[$dtype]['label']))
                    $opts['label'] = $options[$dtype]['label'];
                if (isset($options[$dtype]['type']) && !empty($options[$dtype]['type'])) {
                    // default it DateType so we change it only if DateTimeType
                    if ($options[$dtype]['type'] == 'DateTimeType')
                        $dateType = DateTimeType::class;
                }
                $builder->add($dtype, $dateType, $opts);
            }
        }
        // add custom file name
        if (isset($options['customName']['enabled']) && $options['customName']['enabled'] == true) {
            $cfnLabel = 'Custom name';
            if (isset($options['customName']['label']) && !empty($options['customName']['label']))
                $cfnLabel = $options['customName']['label'];
            $opts = [
                'by_reference' => false,
                'mapped' => true,
                'label' => $cfnLabel,
            ];

            $builder->add('customName', TextType::class, $opts);
        }
    }

    public function buildView(FormView $view, FormInterface $form, array $options) {
        foreach (array_merge($this->baseNomOpts,['issuedOn'=>'issuedOn', 'validFrom'=>'validFrom', 'validTo'=>'validTo', 'customName'=>'customName','groupBy' => 'groupBy', 'expandAllGroups'=>'expandAllGroups']) as $nomKey => $nomType) {
            if (isset($options[$nomKey])) {
                $view->vars[$nomType] = $options[$nomKey];
            }
        }
        $view->vars['baseNomOpts'] = $this->baseNomOpts;
        $view->vars['hideDelete'] = $options['hideDelete'];

    }



    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => FileEntity::class,
            'mapped' => true,
            //'inherit_data' => true, NO inherit. breaks things.
            'by_reference' => true, // if false then collections gets deleted on update and readded!!!
            'maxSize' => "20M",
            'mime' => ['image', 'text'],
            'url' => false,
            'baseNom1' => [ 'enabled' => false, 'nomKey' => false, 'label' => null ],
            'baseNom2' => [ 'enabled' => false, 'nomKey' => false, 'label' => null ],
            'baseNom3' => ['enabled' => false, 'nomKey' => false, 'label' => null ],
            'baseNom4' => ['enabled' => false, 'nomKey' => false, 'label' => null ],

            //valid from file date(time)
            'validFrom' => [ 'enabled' => false, 'label' => null, 'type' => 'DateType' ],

            //valid to file date(time)
            'validTo' => [ 'enabled' => false, 'label' => null, 'type' => 'DateType' ],// type: DateType/DateTimeType;

            // issuedOn file date(time)
            'issuedOn' => [ 'enabled' => false, 'label' => null, 'type' => 'DateType' ],

            'customName' => [ 'enabled' => false, 'label' => 'File name'],

            'required' => false, // allow file to be empty for entity. ONLY possible when not files collection!!!
            'resize_image' => ['max_width'=>false, 'max_height'=>false, 'keep_ratio'=>true],
            'hideDelete' => false,
        ]);
    }


    private function resize_image($src, $dst, $mime, $max_width, $max_height, $keep_aspect_ratio) {
        $source = false;
        switch ($mime) {
            case 'image/gif':
                $source = @imagecreatefromgif($src);
                break;
            case 'image/jpeg':
                $source = @imagecreatefromjpeg($src);
                break;
            case 'image/png':
                $source = @imagecreatefrompng($src);
                break;
        }
        if (!$source) {
            return false;
        }

        $width = imagesx($source);
        $height = imagesy($source);

        $start_x = $start_y = 0;
        if ($keep_aspect_ratio) {
            $scale = max($max_width ? $width / $max_width : -INF, $max_height ? $height / $max_height : -INF);
            $new_width = $width / $scale;
            $new_height = $height / $scale;
            $img_width = $max_width ?: $new_width;
            $img_height = $max_height ?: $new_height;
            $start_x = round(($img_width - $width / $scale) / 2);
            $start_y = round(($img_height - $height / $scale) / 2);
        } else {
            $scale = min($max_width ? $width / $max_width : INF, $max_height ? $height / $max_height : INF);
            $img_width = $new_width = $max_width ? $max_width : $width / $scale;
            $img_height = $new_height = $max_height ? $max_height : $height / $scale;
        }

        $result = imagecreatetruecolor($img_width, $img_height);
        imagefill($result, 0, 0, imagecolorallocatealpha($result, 0, 0, 0, 127));
        imagealphablending($result, false);
        imagesavealpha($result, true);
        imagecopyresampled($result, $source, $start_x, $start_y, 0, 0, $new_width, $new_height, $width, $height);

        //header('Content-Type: image/png');imagepng($result);die();
        imagepng($result, $dst);
        imagedestroy($result);
        imagedestroy($source);
        return true;
    }
}
