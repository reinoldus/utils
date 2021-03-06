<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 24.04.15
 * Time: 21:20
 */

namespace Reinoldus\Doctrine\Validator;


use Reinoldus\Doctrine\DbService\BaseService;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\Validator\AbstractValidator;
use Zend\Validator\Exception;

class UniqueValue extends AbstractValidator {

	/**
	 * @var array
	 */
	private $options;

	const EXISTS        = 'exists';

	/**
	 * @var array
	 */
	protected $messageTemplates = array(
		self::EXISTS        => 'Value already exists'
	);

	public function __construct(array $options) {
		parent::__construct($options);
		$this->options = $options;

		$this->abstractOptions['messageTemplates'] = $this->messageTemplates;
	}

	/**
	 * Returns true if and only if $value meets the validation requirements
	 *
	 * If $value fails validation, then this method returns false, and
	 * getMessages() will return an array of messages that explain why the
	 * validation failed.
	 *
	 * @param  mixed $value
	 * @return bool
	 * @throws Exception\RuntimeException If validation of $value is impossible
	 */
	public function isValid($value)
	{

		/**
		 * @var BaseService
		 */
		$res = $this->options['service']->findBy(array(
			$this->options['fields'] => $value,
			'user' => $this->options['user']
		));

		if(empty($res)) {
			return true;
		}

		//TODO THE EXCEPT SHIT IS MESSED UP!
		if(array_key_exists('except', $this->options)) {
			if(($res) ? 'true' : 'false' == $this->options['except']) {
				return true;
			}
		}

		if(isset($this->options['values']['id']) and $res[0]->getId() === $this->options['values']['id']) {
			return true;
		}

		if(!empty($res)) {
			$this->error(self::EXISTS);
			return false;
		}

		return true;
	}
}