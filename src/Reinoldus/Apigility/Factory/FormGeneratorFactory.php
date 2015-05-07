<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 13.03.15
 * Time: 18:57
 */

namespace Reinoldus\Apigility\Factory;


use Reinoldus\Apigility\Service\FormGenerator;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ApigilityFormGeneratorFactory  implements FactoryInterface {

	/**
	 * Create service
	 *
	 * @param ServiceLocatorInterface $serviceLocator
	 * @return mixed
	 */
	public function createService(ServiceLocatorInterface $serviceLocator)
	{

		return new FormGenerator($serviceLocator);
	}
}