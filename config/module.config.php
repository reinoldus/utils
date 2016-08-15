<?php
return array(
	'service_manager' => array(
		'abstract_factories' => array(
			'Reinoldus\Doctrine\Factory\EntityServiceAbstractFactory'
		),
		'aliases' => array(

		),
		'factories' => array(
			'Reinoldus\Doctrine\Mapper\ApigilityDoctrineMapper' => 'Reinoldus\Doctrine\Factory\ApigilityDoctrineMapperFactory',
			'Reinoldus\ZfcUser\Mapper\ZfcUserOauth2Mapper' => function($sl) {
				return new Reinoldus\ZfcUser\Mapper\ZfcUserOauth2Mapper($sl);
			},
			'Reinoldus\Apigility\Service\FormGenerator' => 'Reinoldus\Apigility\Factory\FormGenerator'
		),
		'invokables' => array(
			'Reinoldus\Doctrine\Mapper\ApigilityDoctrineToArray' => 'Reinoldus\Doctrine\Mapper\ApigilityDoctrineToArray'
		),
	),
	'validators' => array(
		'factories' => array(
			'Reinoldus\Doctrine\Validator\UniqueValueValidator' => 'Reinoldus\Doctrine\Validator\UniqueValueFactory',
			'Reinoldus\Doctrine\Validator\NotEmptyArrayAndValue' => 'Reinoldus\Doctrine\Validator\NotEmptyArrayAndValueFactory',
			'Reinoldus\Doctrine\Validator\ArrayNotEmptyAndRegex' => 'Reinoldus\Doctrine\Validator\ArrayNotEmptyAndRegexFactory'
		),
		'shared' => array(
			'Reinoldus\Doctrine\Validator\UniqueValueValidator' => false
		)
	),
	'validator_metadata' => array(
		'Reinoldus\Doctrine\Validator\UniqueValueValidator' => array(
			'service' => 'string',
			'fields' => 'string',
			'except' => 'string'
		),
		'Reinoldus\Doctrine\Validator\NotEmptyArrayAndValue' => array(
			'message' => 'string',
		),
		'Reinoldus\Doctrine\Validator\ArrayNotEmptyAndRegex' => array(
			'message' => 'string',
			'regex' => 'string'
		)
	),
	'view_helpers' => array(
		'factories'=> array(
			'routeCheck' => function($pluginManager) {
				/**
				 * @var \Zend\View\HelperPluginManager $pluginManager
				 */
				return new Reinoldus\ViewHelper\RouteCheck($pluginManager->getServiceLocator()->get('Application'));
			}
		)
	),
	'view_manager' => array(
		'template_map' => array(
			'reinoldus/form/formRowPartial'           => __DIR__ . '/../view/apigility/form_row_partial.phtml',
			'reinoldus/listjs/defaultPartial'           => __DIR__ . '/../view/listjs/default_partial.phtml',
			'reinoldus/listjs/defaultPartialNext'           => __DIR__ . '/../view/listjs/default_partial_next.phtml',
			'reinoldus/listjs/blankPartial'           => __DIR__ . '/../view/listjs/blank_partial.phtml',
		),
		'template_path_stack' => array(
			__DIR__ . '/../view',
		),
	),
);