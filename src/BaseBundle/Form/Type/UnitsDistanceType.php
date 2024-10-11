<?php

namespace BaseBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;


class UnitsDistanceType extends AbstractType {
	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults([
			'units' => ['nm', 'sm', 'km'],
			'default_unit' => 'nm',
			'default_unit_getter' => 'getDistanceUnits',
			'normalized_unit' => 'm',
		]);
	}

	public function getParent() {
		return UnitsType::class;
	}
}
