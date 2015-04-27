<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 24.04.15
 * Time: 17:22
 */

namespace Reinoldus\ZfcUser\Mapper;


use Zend\ServiceManager\ServiceManager;
use ZF\MvcAuth\Identity\IdentityInterface;
use ZfcUser\Service\User;

class ZfcUserOauth2Mapper {

	/**
	 * @var ServiceManager
	 */
	private $sm;

	/**
	 * @var User
	 */
	private $zfcUserService;

	public function __construct(ServiceManager $sm) {
		$this->sm = $sm;

		/**
		 * @var User $zfcUserService
		 */
		$this->zfcUserService = $this->sm->get('zfcuser_user_service');
	}

	/**
	 * @param IdentityInterface $oauth2Request
	 * @return mixed
	 */
	public function getUserFromOauth2Request(IdentityInterface $oauth2Request) {

		return $this->zfcUserService->getUserMapper()->findByEmail($oauth2Request->getName());
	}
}