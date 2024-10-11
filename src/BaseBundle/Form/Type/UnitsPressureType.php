<?php

namespace BaseBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;


class UnitsPressureType extends AbstractType {
	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults([
			'units' => ['hPa', 'inHg', 'mmHg'],
			'default_unit' => 'hPa',
			'default_unit_getter' => 'getPressureUnits',
			'normalized_unit' => 'hPa',
		]);
	}

	public function getParent() {
		return UnitsType::class;
	}
}
