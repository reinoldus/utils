<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 24.04.15
 * Time: 21:20
 */

namespace Reinoldus\Doctrine\Validator;


use Reinoldus\Doctrine\DbService\BaseService;
use Zend\Validator\AbstractValidator;
use Zend\Validator\Exception;

class ArrayNotEmptyAndRegex extends AbstractValidator {

	/**
	 * @var array
	 */
	private $options;

	const REGEX_FAIL        = 'REGEX_FAIL';
    const ENTER_VALUE           = 'ENTER_VALUE';

	/**
	 * @var array
	 */
	protected $messageTemplates = array(
		self::ENTER_VALUE         => 'Bitte ausfüllen!',
		self::REGEX_FAIL    => 'Dies ist keine gültige Eingabe!'
	);

	/**
	 * ArrayNotEmptyAndRegex constructor.
	 * @param array $options
	 */
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
        if(is_array($value) && !empty($value)) {
            foreach($value as $item) {
	            if(!preg_match($this->options['regex'], $item)) {
		            $this->error(self::REGEX_FAIL);
		            return false;
	            }
            }
        } else {
            if(empty(trim($value))) {
                $this->error(self::ENTER_VALUE);
                return false;
            }
        }

		return true;
	}
}