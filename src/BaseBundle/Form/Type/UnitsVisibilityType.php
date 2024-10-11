<?php

namespace BaseBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;


class UnitsVisibilityType extends AbstractType {
	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults([
			'units' => ['m', 'sm'],
			'default_unit' => 'm',
			'default_unit_getter' => 'getVisibilityUnits',
			'normalized_unit' => 'm',
		]);
	}

	public function getParent() {
		return UnitsType::class;
	}
}
