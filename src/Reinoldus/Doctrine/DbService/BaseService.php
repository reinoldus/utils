<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 10/11/14
 * Time: 11:45 PM
 */

namespace Reinoldus\Doctrine\DbService;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;

use Doctrine\ORM\NoResultException;
use Reinoldus\Doctrine\Entity\BaseEntity;
use Zend\ServiceManager\ServiceLocatorInterface;

class BaseService
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
	 * @var string
	 */
	protected $entityName;

	/**
	 * @param EntityManager $em
	 * @param ServiceLocatorInterface $serviceLocator
	 * @param string $entityName
	 */
	public function __construct(EntityManager $em, ServiceLocatorInterface $serviceLocator, $entityName=null)
	{
		$this->em = $em;
		$this->serviceLocator = $serviceLocator;
		$this->entityName = $entityName;
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
		if($this->entityName !== null) {
			$repository = $this->em->getRepository($this->entityName);
		} else {
			$repository = $this->em->getRepository(str_replace('Service', '', str_replace('Service', 'Entity', get_class($this))));
		}

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

	/**
	 * @param integer $id
	 * @return BaseEntity|null
	 * @throws NoResultException
	 */
	public function findById($id)
	{
		/**
		 * @var $model BaseEntity
		 */
		$model = $this->getRepository()->findOneBy(array("id" => $id));

		if($model === null) {
			throw new NoResultException();
		}

		return $model;
	}

	/**
	 * @return BaseEntity[]|ArrayCollection
	 */
	public function findAll()
	{
		$model = $this->getRepository()->findAll();

		return $model;
	}

	/**
	 * @param array $criteria
	 * @return BaseEntity[]
	 */
	public function findBy(array $criteria)
	{
		$models = $this->getRepository()->findBy($criteria);

		return $models;
	}

	/**
	 * @param array $order should look like this: array('title'=>'asc')
	 * @return array
	 */
	public function findAllOrderBy($order)
	{
		return $this->getRepository()->findBy(array(), $order);
	}
}