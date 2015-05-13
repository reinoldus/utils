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
	    )
    ),
	'validators' => array(
		'factories' => array(
			'Reinoldus\Doctrine\Validator\UniqueValue' => 'Reinoldus\Doctrine\Validator\UniqueValueFactory'
		),
	),
	'validator_metadata' => array(
		'Reinoldus\Doctrine\Validator\UniqueValue' => array(
			'service' => 'string',
			'fields' => 'string',
		),
	),
);
