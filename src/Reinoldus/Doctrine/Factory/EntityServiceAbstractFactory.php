<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 20.03.15
 * Time: 10:25
 */

namespace Reinoldus\Doctrine\Factory;


use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EntityServiceAbstractFactory implements AbstractFactoryInterface {

	/**
	 * Determine if we can create a service with name
	 *
	 * @param ServiceLocatorInterface $serviceLocator
	 * @param $name
	 * @param $requestedName
	 * @return bool
	 */
	public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
	{
		if(stristr($requestedName, 'Service') && class_exists(str_replace('Service', 'Entity', $requestedName))) {

			if(get_parent_class($requestedName) === "Reinoldus\Doctrine\DbService\BaseService") {

				return true;
			}
		}

		return false;
	}

	/**
	 * Create service with name
	 *
	 * @param ServiceLocatorInterface $serviceLocator
	 * @param $name
	 * @param $requestedName
	 * @return mixed
	 */
	public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
	{
		return new $requestedName($serviceLocator->get('\Doctrine\ORM\EntityManager'), $serviceLocator);
	}
}