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

class NotEmptyArrayAndValue extends AbstractValidator {

	/**
	 * @var array
	 */
	private $options;

	const ARRAY_NOT_ALL        = 'ARRAY_NOT_ALL';
    const SINGLE_NOT           = 'SINGLE_NOT';

	/**
	 * @var array
	 */
	protected $messageTemplates = array(
		self::ARRAY_NOT_ALL         => 'Bitte alle Felder ausfüllen!',
        self::SINGLE_NOT            => 'Bitte Feld ausfüllen'
	);

	public function __construct(array $options) {
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
        if(is_array($value)) {
            foreach($value as $name) {
                if(empty(trim($name))) {

                    $this->error(self::ARRAY_NOT_ALL);

                    return false;
                }
            }
        } else{
            if(empty(trim($value))) {
                $this->error(self::SINGLE_NOT);
                return false;
            }
        }

		return true;
	}
}