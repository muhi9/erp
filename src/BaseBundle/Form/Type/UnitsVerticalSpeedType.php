<?php

namespace BaseBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;


class UnitsVerticalSpeedType extends AbstractType {
	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults([
			'units' => ['ft/m', 'm/s'],
			'default_unit' => 'ft/m',
			'default_unit_getter' => 'getVerticalSpeedUnits',
			'normalized_unit' => 'm/s',
		]);
	}

	public function getParent() {
		return UnitsType::class;
	}
}
