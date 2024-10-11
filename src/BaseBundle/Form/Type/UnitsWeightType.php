<?php

namespace BaseBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;


class UnitsWeightType extends AbstractType {
	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults([
			'units' => ['lb', 'kg'],
			'default_unit' => 'lb',
			'default_unit_getter' => 'getWeightUnits',
			'normalized_unit' => 'kg',
		]);
	}

	public function getParent() {
		return UnitsType::class;
	}
}
