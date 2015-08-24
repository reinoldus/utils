<?php

namespace Reinoldus\Doctrine\Validator;

use DoctrineModule\Validator\UniqueObject;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;


class UniqueValueFactory implements FactoryInterface, MutableCreationOptionsInterface
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
		if (isset($this->options['service'])) {
			$service = $validators->getServiceLocator()->get($this->options['service']);
            $auth = $validators->getServiceLocator()->get('zfcuser_auth_service');

			$fields = $this->options['fields'];

			return new UniqueValue(ArrayUtils::merge(
				$this->options,
				array(
					'service' => $service,
					'fields' => $fields,
                    'user' => $auth->getIdentity()
				)
			));
		}
	}
}