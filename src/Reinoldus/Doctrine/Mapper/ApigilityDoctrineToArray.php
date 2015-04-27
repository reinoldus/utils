<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 31.03.15
 * Time: 14:49
 */

namespace Reinoldus\Doctrine\Mapper;


use Reinoldus\Doctrine\Entity\BaseEntity;

class ApigilityDoctrineToArray {


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

}