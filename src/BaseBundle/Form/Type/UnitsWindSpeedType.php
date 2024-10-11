<?php

namespace BaseBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;


class UnitsWindSpeedType extends AbstractType {
	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults([
			'units' => ['kt', 'm/s', 'mph', 'km/h'],
			'default_unit' => 'kt',
			'default_unit_getter' => 'getWindSpeedUnits',
			'normalized_unit' => 'm/s',
		]);
	}

	public function getParent() {
		return UnitsType::class;
	}
}
