<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
	        'Application\Factory\ApigilityFormGeneratorFactory'
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
