<?php
namespace BaseBundle\Form\Type;

use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Form\CallbackTransformer;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Query\Parameter;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\OptionsResolver\Options;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityManager;

use CoursesBundle\Entity\CourseElement;
use CoursesBundle\Repository\CourseElementRepository;
use BaseBundle\Form\Type\CourseInputType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class CourseElementsType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options){

    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'class' => CourseElement::class,
            'url' => 'course_element_autocomplete',
            'link' => 'course_edit',
            'choice_label' => 'fullName',
            'filterPredefined' => [
                'user' => null,
                'part' => false,
                'required_user' => false,
            ],
            'choice_label' => 'exerciseTree',

        ));

    }

    public function buildView(FormView $view, FormInterface $form, array $options){
        
    }

    public function getParent() {
        return ACType::class;
    }
}

