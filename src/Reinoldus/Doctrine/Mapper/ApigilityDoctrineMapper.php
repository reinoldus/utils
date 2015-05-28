<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 18.03.15
 * Time: 14:05
 */

namespace Reinoldus\Doctrine\Mapper;

use Reinoldus\Doctrine\Entity\BaseEntity;
use Zend\ServiceManager\ServiceLocatorInterface;

class ApigilityDoctrineMapper {

	/**
	 * @var ServiceLocatorInterface
	 */
	private $serviceLocator;

	public function __construct(ServiceLocatorInterface $serviceLocator) {
		$this->serviceLocator = $serviceLocator;
	}


	public function create(BaseEntity $entity, $data) {
		$attributes = $entity->getAttributes();

		if(!is_object($data)) {
			throw new \Exception("No data object provided");
		}

		foreach($attributes as $attribute) {
			if(property_exists($data, $attribute)) {
				if(method_exists($entity, "set" . $attribute)) {
					$entity->{"set" . $attribute}($data->{$attribute});
				}
			}
		}

		return $entity;
	}

	public function getArrayAll($all) {
		$return = [];
		foreach($all as $single) {
			/**
			 * @var BaseEntity $single
			 */
			$attributes = $single->getAttributes();
			$putArray = array();
			foreach($attributes as $attribute) {
				if(method_exists($single, "get" . $attribute)) {
					$putArray[$attribute] = $single->{"get" . $attribute}();
				}
			}

			$return[] = $putArray;
		}

		return $return;
	}

	public function getArraySingle(BaseEntity $single) {
		$attributes = $single->getAttributes();
		$putArray = array();
		foreach($attributes as $attribute) {
			if(method_exists($single, "get" . $attribute)) {
				$putArray[$attribute] = $single->{"get" . $attribute}();
			}
		}

		return $putArray;
	}

	public function updateSingle($data, BaseEntity $model, $except=array()) {

		foreach($model->getAttributes() as $attribute) {
			if(!in_array($attribute, $except) && property_exists($data, $attribute)) {
				if(method_exists($model, "set" . $attribute)) {
					$model->{"set" . $attribute}($data->{$attribute});
				}
			}
		}

		return $model;
	}
}