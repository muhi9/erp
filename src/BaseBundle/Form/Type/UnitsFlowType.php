<?php

namespace BaseBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;


class UnitsFlowType extends AbstractType {
	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults([
			'units' => ['US gal/h', 'l/h'],
			'default_unit' => 'US gal/h',
			'default_unit_getter' => 'getFlowUnits',
			'normalized_unit' => 'l/h',
		]);
	}

	public function getParent() {
		return UnitsType::class;
	}
}
