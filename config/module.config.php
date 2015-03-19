<?php
return array(
    'service_manager' => array(
        'abstract_factories' => array(
        ),
        'aliases' => array(

        ),
	    'factories' => array(
		    'Reinoldus\Doctrine\Mapper\ApigilityDoctrineMapper' => 'Reinoldus\Doctrine\Factory\ApigilityDoctrineMapperFactory'
	    ),
	    'invokables' => array(
	    )
    ),
);
