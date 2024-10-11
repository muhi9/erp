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
use Symfony\Component\Form\Extension\Core\Type\TextType;

class FileInputCollectionType extends FileInputType {
    public function configureOptions(OptionsResolver $resolver) {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'groupBy' => false,
            'expandAllGroups' => false,
            'gridList' => false,
            /* set gridList=>
                ['columns' => [
                    'bnomType1'=>'Type name',
                    'name'=>'File Name',
                    'customName' => 'Custom Name',
                    'issuedOn' => 'Issued',
                    'validFrom' => 'Valid From',
                    'validTo' => 'Valid To',
                    ....]
                ]
            */
        ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options) {
        if (isset($options['gridList']) && !empty($options['gridList'])) {
            $view->vars['gridList'] = $options['gridList'];
        }
        if (isset($options['groupBy']) && !empty($options['groupBy'])) {
            // files collection
            $vals = $view->parent->vars['data']->getValues();
            $grouped = [];
            $gKey = $options['groupBy'];
            foreach ($vals as $file) {
                $fgkey = 'other';
                if (method_exists($file, ('get' . ucfirst($options['groupBy'])))) {
                    $fgkey = $file->{ 'get' . ucfirst($options['groupBy'])}->getName();
                }
                if (method_exists($file,('get' . ucfirst($this->baseNomOpts[$options['groupBy']])))) {
                    $fgkey = $file->{ 'get' . ucfirst($this->baseNomOpts[$options['groupBy']])}()->getName();
                }
                if (!isset($grouped[$fgkey]))
                    $grouped[$fgkey] = [$file->getId()];
                else
                    $grouped[$fgkey][] = $file->getId();

            }
            $view->vars['grouped'] = $grouped;
            if (isset($options['expandAllGroups']))
                $view->vars['expandAllGroups'] = $options['expandAllGroups'];
        }
        parent::buildView($view, $form, $options);
    }
}
