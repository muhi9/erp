<?php

namespace BaseBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;


class UnitsVelocityType extends AbstractType {
	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults([
			'units' => ['kt', 'mph', 'km/h'],
			'default_unit' => 'kt',
			'default_unit_getter' => 'getVelocityUnits',
			'normalized_unit' => 'm/s',
		]);
	}

	public function getParent() {
		return UnitsType::class;
	}
}
