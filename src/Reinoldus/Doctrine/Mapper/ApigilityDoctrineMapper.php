<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 18.03.15
 * Time: 14:05
 */

namespace Reinoldus\Doctrine\Mapper;

use Doctrine\Common\Collections\Collection;
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

	/**
	 * Not safe for every purpose...
	 * Use only if you are sure the vars are written sanely:
	 * a_b not a_ or something...
	 *
	 * @param $string
	 * @return mixed
	 */
	private function convertToCamelCase($string) {
		while(($position = strpos($string, '_')) !== false) {
			$letter = substr($string, $position + 1, 1);
			$letter = strtoupper($letter);

			$string = substr_replace($string, $letter, $position, 2);
		}

		return $string;
	}

	/**
	 *
	 * @param $string
	 * @return mixed
	 */
	public function convertFromCamelCase($string) {
		return ltrim(strtolower(preg_replace('/[A-Z]/', '_$0', $string)), '_');
	}

	public function create(BaseEntity $entity, $data, $convertToCamelCase = false) {
		$attributes = $entity->getAttributes();

		if(!is_object($data)) {
			throw new \Exception("No data object provided");
		}

		if($convertToCamelCase) {
			$newData = new \stdClass();
			foreach($data as $key => $value) {
				if($key != $this->convertToCamelCase($key)) {
					$newData->{$this->convertToCamelCase($key)} = $value;
				} else {
					$newData->{$key} = $value;
				}
			}
			$data = $newData;
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

	public function getArrayAll($all, $toUnderscore=false) {
		$return = [];
		foreach($all as $single) {
			/**
			 * @var BaseEntity $single
			 */
			$attributes = $single->getAttributes();
			$putArray = array();
			foreach($attributes as $attribute) {
				if(method_exists($single, "get" . $attribute)) {

					if($toUnderscore) {
						$attribute_name = $this->convertFromCamelCase($attribute);
					} else {
						$attribute_name = $attribute;
					}

					$putArray[$attribute_name] = $single->{"get" . $attribute}();
					if(method_exists($putArray[$attribute_name], "getArrayCopy")) {
						$putArray[$attribute_name] = $putArray[$attribute_name]->getArrayCopy();
					}
					if($putArray[$attribute_name] instanceof Collection) {
						$putArray[$attribute_name] = $putArray[$attribute_name]->getValues();
						$putArray[$attribute_name] = array_map(array($this, "getArraySingle"), $putArray[$attribute_name], array(true));
					}

				}
			}

			$return[] = $putArray;
		}

		return $return;
	}

	public function getArraySingle(BaseEntity $single=null, $toUnderscore=false) {

		if($single === null) {
			return [];
		}

		$attributes = $single->getAttributes();
		$putArray = array();
		foreach($attributes as $attribute) {

			if($toUnderscore) {
				$attribute_name = $this->convertFromCamelCase($attribute);
			} else {
				$attribute_name = $attribute;
			}

			if(method_exists($single, "get" . $attribute)) {
				$putArray[$attribute_name] = $single->{"get" . $attribute}();
			}
			if(method_exists($putArray[$attribute_name], "getArrayCopy")) {
				$putArray[$attribute_name] = $putArray[$attribute]->getArrayCopy();
			}
			if($putArray[$attribute_name] instanceof Collection) {
				$putArray[$attribute_name] = $putArray[$attribute_name]->getValues();
				$putArray[$attribute_name] = array_map(array($this, "getArraySingle"), $putArray[$attribute_name], array(true));
			}
		}

		return $putArray;
	}

	public function updateSingle($data, BaseEntity $model, $except=array(), $convertToCamelCase = false) {
		if($convertToCamelCase) {
			$newData = new \stdClass();
			foreach($data as $key => $value) {
				if($key != $this->convertToCamelCase($key)) {
					$newData->{$this->convertToCamelCase($key)} = $value;
				} else {
					$newData->{$key} = $value;
				}
			}
			$data = $newData;
		}

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