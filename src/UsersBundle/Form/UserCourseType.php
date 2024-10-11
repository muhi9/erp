<?php

namespace UsersBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use BaseBundle\Form\Type\BaseNomType;
class UserCourseType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('course', BaseNomType::class, ['baseNom' => 'course.course', 'hasChild'=>'subCourse','attr'=>['placeholder'=>'user.form.course.course']])
            ->add('subCourse', BaseNomType::class, ['baseNom' => 'course.subCourse','hasParent'=>'course', 'hasChild'=>'issue','attr'=>['placeholder'=>'user.form.course.subCourse']])
            ->add('issue', BaseNomType::class, ['baseNom' => 'course.issue', 'hasParent'=>'subCourse','hasChild'=>'revision','attr'=>['placeholder'=>'user.form.course.issue']])
            ->add('revision', BaseNomType::class, ['baseNom' => 'course.revision', 'hasParent'=>'issue','attr'=>['placeholder'=>'user.form.course.revision']])
            ->add('start',null,['attr'=>['placeholder'=>'user.form.course.start']])
            ->add('end',null,['attr'=>['placeholder'=>'user.form.course.end']]);

        $builder->addModelTransformer(new \Symfony\Component\Form\CallbackTransformer(
            function ($data) {
                return $data;
            },
            function ($data) {
                $data->setIsFinish(false);
                if(!empty($data->getEnd())){
                 $data->setIsFinish(true);
                }
                return $data;
            }
        ));

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UsersBundle\Entity\UserCourse',
            'label_format' => 'user.form.course.%name%',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'usersbundle_usercourses';
    }


}
