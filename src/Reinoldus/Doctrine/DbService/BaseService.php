<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 10/11/14
 * Time: 11:45 PM
 */

namespace Reinoldus\Doctrine\DbService;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;

use Doctrine\ORM\NoResultException;
use Zend\ServiceManager\ServiceLocatorInterface;

abstract class BaseService
{
	/**
	 * @var EntityManager
	 */
	protected $em;

	/**
	 * @var ServiceLocatorInterface
	 */
	protected $serviceLocator;

	/**
	 * @param EntityManager $em
	 * @param ServiceLocatorInterface $serviceLocator
	 */
	public function __construct(EntityManager $em, ServiceLocatorInterface $serviceLocator)
	{
		$this->em = $em;
		$this->serviceLocator = $serviceLocator;
	}

	/**
	 * @param $model
	 */
	public function create($model) {
		$this->em->persist($model);
		$this->em->flush();
	}

	/**
	 * @return \Doctrine\ORM\EntityRepository
	 */
	public function getRepository()
	{

		$repository = $this->em->getRepository(str_replace('Service', '', str_replace('Service', 'Entity', get_class($this))));

		return $repository;
	}

	/**
	 * @return QueryBuilder
	 */
	public function createDefaultQueryBuilder()
	{
		$qb = $this->getRepository()->createQueryBuilder('a');
		return $qb;
	}

	/**
	 * @return EntityManager
	 */
	public function getEm() {
		return $this->em;
	}

}