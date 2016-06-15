<?php

namespace Reinoldus\Doctrine\Validator;

use DoctrineModule\Validator\UniqueObject;
use Zend\Http\Request;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;
use Zend\Validator\ValidatorPluginManager;


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
		/**
		 * @var \Zend\Validator\ValidatorPluginManager $validators
		 */
		if (isset($this->options['service'])) {
			$service = $validators->getServiceLocator()->get($this->options['service']);
            $auth = $validators->getServiceLocator()->get('zfcuser_auth_service');

			/**
			 * @var \ZF\ContentNegotiation\Request $request
			 */
			$request = $validators->getServiceLocator()->get('Request');
			$values = json_decode($request->getContent(), true);

			$fields = $this->options['fields'];

			return new UniqueValue(ArrayUtils::merge(
				$this->options,
				array(
					'service' => $service,
					'fields' => $fields,
                    'user' => $auth->getIdentity(),
					'values' => $values
				)
			));
		} else {
			return false;
		}
	}
}