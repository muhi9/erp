<?php

namespace BaseBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;


class UnitsCenterOfGravityType extends AbstractType {
	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults([
			'units' => ['in', 'mm'],
			'default_unit' => 'in',
			'default_unit_getter' => 'getCenterOfGravityUnits',
			'normalized_unit' => 'm',
		]);
	}

	public function getParent() {
		return UnitsType::class;
	}
}
