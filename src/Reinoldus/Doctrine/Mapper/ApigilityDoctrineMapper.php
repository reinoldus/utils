<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 18.03.15
 * Time: 14:05
 */

namespace Reinoldus\Doctrine\Mapper;

class ApigilityDoctrineMapper {

	/**
	 * @var ServiceLocatorInterface
	 */
	private $serviceLocator;

	public function __construct(ServiceLocatorInterface $serviceLocator) {
		$this->serviceLocator = $serviceLocator;
	}

}