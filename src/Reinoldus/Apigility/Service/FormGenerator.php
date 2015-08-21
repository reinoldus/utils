<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 13.03.15
 * Time: 17:21
 */

namespace Reinoldus\Apigility\Service;


use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorInterface;

class FormGenerator {

	/**
	 * @var array
	 */
	private $config;

	/**
	 * @var ServiceLocatorInterface
	 */
	private $serviceLocator;

	/**
	 * @var array
	 */
	private $jsConfig = array();

	/**
	 * @var array
	 */
	private $dataBindArray = array();

	/**
	 * @var string
	 */
	private $prefix = 'apgi_';

	public function __construct(ServiceLocatorInterface $serviceLocator) {

		$this->serviceLocator = $serviceLocator;
		$this->config = $serviceLocator->get('config');
		$this->form = new Form();
	}

	/**
	 * @param $input
	 * @param $orders
	 * @param $name
	 */
	public function makeInput($input, $orders, $name) {
//		if(array_key_exists('after', $input)) {
//			$orders[$input['after']] = $orders[$input['after']] - 10;
//		}

		if(!array_key_exists('type', $input)) {
			$input['type'] = $this->guessType($name);
		}

		$this->form->add(array(
			'name' => $name,
			'type' => $input['type'],
			'options' => array(
				'label' => $input['label'],
			),
			'attributes' => array(
				'class' => 'form-control ',
				'placeholder' => 'Bitte ausfüllen',
				'id' => $this->prefix . $name,
				//kjs
				'data-bind' => $this->makeDataBind($input, $name),
			)
		)/*, array(
			'priority' => $orders[$name]
		)*/);

		if(isset($input['value_options'])) {
			$this->form->get($name)->setAttribute('options', $input['value_options']);
		}

	}

    /**
     * @param string $name
     * @param null $formName
     * @throws \Exception
     * @return Form
     */
	public function makeForm($name, $formName=null) {
		$this->form = new Form();
		$this->jsConfig = "";
		$config = $this->config['input_filter_specs'][$name];

		if(isset($this->config['form_config'])) {
			$formConfig = $this->config['form_config'][str_replace('Validator', 'Form', $name)];
		} else {
            throw new \Exception("Form config not found");
        }

        if($formName !== NULL) {
            $formConfig = $formConfig[$formName];
        }

		$order = 1000 * count($config);
		$orders = array();

		foreach($formConfig as $name => $inpu) {
			$orders[$name] = $order;
			$order = $order - 1000;
		}

		if(isset($formConfig)) {
			foreach($formConfig as $name => $input) {
				$this->makeInput($input, $orders, $name);
//				if(array_key_exists('after', $input)) {
//					$orders[$input['after']] = $orders[$input['after']] - 10;
//				}
//
//				if(!array_key_exists('type', $input)) {
//					$input['type'] = $this->guessType($name);
//				}
//
//				$form->add(array(
//					'name' => $name,
//					'type' => $input['type'],
//					'options' => array(
//						'label' => $input['label'],
//					),
//					'attributes' => array(
//						'class' => 'form-control '.$classes,
//						'placeholder' => 'Bitte ausfüllen',
//						'id' => $this->prefix . $name,
//						//kjs
//						'data-bind' => $this->makeDataBind($input, $name),
//					)
//				), array(
//					'priority' => $orders[$name]
//				));
			}
		} else {
			throw new \Exception("No form config specified");
		}

		$this->form->add(array(
			'name' => 'id',
			'type' => 'hidden',
			'attributes' => array(
				'id' => 'id',
				'class' => 'btn btn-primary',
				'data-bind' => 'click:save',
			),
		));


		$this->form->add(array(
			'name' => 'submit',
			'type' => 'Submit',
			'attributes' => array(
				'value' => 'Goo',
				'id' => 'submitbutton',
				'class' => 'btn btn-primary save',
				'data-bind' => 'click:save',
			),
		));

		$this->form->add(array(
			'name' => 'update',
			'type' => 'submit',
			'attributes' => array(
				'value' => 'Bearbeiten',
				'class' => 'btn btn-primary update',
				'data-bind' => 'click:updateFunction',
				'style' => 'display:none'
			),
		));

		$this->form->add(array(
			'name' => 'abort',
			'type' => 'Submit',
			'attributes' => array(
				'value' => 'Abbrechen',
				'class' => 'btn btn-primary cancel',
				'data-bind' => 'click:cancel',
				'style' => 'display:none'
			),
		));

		return $this->form;
	}

	/**
	 * @return string
	 */
	public function getJSConfig() {
		return json_encode($this->jsConfig);
	}

	/**
	 * @return array
	 */
	public function getDataBindArray() {
		return $this->dataBindArray;
	}

	/**
	 * @param $formControl
	 * @param $name
	 * @return string
	 */
	private function makeDataBind($formControl, $name) {
		$dataBind = '';
		$this->jsConfig['kjsVar'][$name] = array(
			'type' => $formControl['type']
		);

		if(isset($formControl['show'])) {
			/**
			 * This implements the show on related element stuff.
			 */
			if($formControl['show']['type'] == 'select') {
				$kjsVar = 'show' . $formControl['show']['relatedElement'] . $formControl['show']['value'];
				$this->dataBindArray['visible'] = 'visible:show' . $formControl['show']['relatedElement'] . $formControl['show']['value'];
				$dataBind .= $this->dataBindArray['visible'] . ', ';
				$this->jsConfig['kjsVar'][$name]['conditionalInput'] = array(
					'var' => $kjsVar,
					'type' => $formControl['show']['type'],
					'relatedElement' => $formControl['show']['relatedElement'],
					'init' => false,
					'showOnValue' => $formControl['show']['value']
				);
			}
		}

		if(isset($formControl['ajaxAdd']) && $formControl['ajaxAdd'] === true) {
			$this->jsConfig['kjsVar'][$name]['ajaxAdd'] = true;

		}

		$dataBind .= 'value:' . $name;
		return $dataBind;
	}

	private function guessType($name) {
		if($name === 'csrf') {
			return 'Zend\Form\Element\Csrf';
		} elseif($name === 'type') {
			return 'select';
		} else {
			return 'text';
		}
	}

	public function setPrefix($prefix) {
		$this->prefix = $prefix;

		return $this;
	}
}