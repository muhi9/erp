<?php

namespace BaseBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;


class UnitsLengthType extends AbstractType {
	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults([
			'units' => ['ft', 'm'],
			'default_unit' => 'ft',
			'default_unit_getter' => 'getLengthUnits',
			'normalized_unit' => 'm',
		]);
	}

	public function getParent() {
		return UnitsType::class;
	}
}
