<?php

namespace BaseBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;


class UnitsTemperatureType extends AbstractType {
	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults([
			'units' => ['C', 'F'],
			'default_unit' => 'C',
			'default_unit_getter' => 'getTemperatureUnits',
			'normalized_unit' => 'C',
		]);
	}

	public function getParent() {
		return UnitsType::class;
	}
}
