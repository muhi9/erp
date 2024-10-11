<?php

namespace BaseBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use CoursesBundle\Entity\CourseElement;

// FILTERS: required_user, user, part, course, subCourse, aircraftManufacturer

class CourseInputType extends AbstractType {

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'class' => CourseElement::class,
            'url' => 'course_element_autocomplete',
            'link' => 'course_edit',
            'choice_label' => 'fullName',
            'filterPredefined' => [
                'user' => null,
                'part' => false,
                'required_user' => false,
            ],
        ]);
    }


    
    public function getParent() {
        return ACType::class;
    }

}