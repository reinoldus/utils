<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 19.03.15
 * Time: 09:30
 */

namespace Reinoldus\Doctrine\Factory;


use Application\Service\Cloak;
use Reinoldus\Doctrine\Mapper\ApigilityDoctrineMapper;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ApigilityDoctrineMapperFactory implements FactoryInterface {

	/**
	 * Create service
	 *
	 * @param ServiceLocatorInterface $serviceLocator
	 * @return mixed
	 */
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new ApigilityDoctrineMapper($serviceLocator);
	}
}