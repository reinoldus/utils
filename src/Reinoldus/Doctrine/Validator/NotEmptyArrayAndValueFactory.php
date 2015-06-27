<?php

namespace Reinoldus\Doctrine\Validator;

use DoctrineModule\Validator\UniqueObject;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;


class NotEmptyArrayAndValueFactory implements FactoryInterface, MutableCreationOptionsInterface
{
	/**
	 * @var array
	 */
	protected $options = array();

	/**
	 * Sets options property
	 *
	 * @param array $options
	 */
	public function setCreationOptions(array $options)
	{
		$this->options = $options;
	}

	/**
	 * Creates the service
	 *
	 * @param ServiceLocatorInterface $validators
	 *
	 * @return UniqueObject
	 */
	public function createService(ServiceLocatorInterface $validators)
	{
        return new NotEmptyArrayAndValue(ArrayUtils::merge(
			$this->options,
            array()
        ));
	}
}