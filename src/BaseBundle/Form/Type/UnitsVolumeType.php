<?php

namespace BaseBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;


class UnitsVolumeType extends AbstractType {
	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults([
			'units' => ['US gal', 'UK gal', 'qt', 'l'],
			'default_unit' => 'US gal',
			'default_unit_getter' => 'getVolumeUnits',
			'normalized_unit' => 'm3',
		]);
	}

	public function getParent() {
		return UnitsType::class;
	}
}
