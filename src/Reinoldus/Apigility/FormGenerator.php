<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 13.03.15
 * Time: 17:21
 */

namespace Reinoldus\Apigility;


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
	}

	/**
	 * @param string $name
	 * @return Form
	 */
	public function makeForm($name) {
		$config = $this->config['input_filter_specs'][$name];

		$form = new Form();

		$order = 1000 * count($config);
		$orders = array();

		foreach($config as $input) {
			$orders[$input['name']] = $order;
			$order = $order - 1000;
		}

		$classes = '';
		foreach($config as $input) {
			$formControl = null;
			if(array_key_exists('description', $input)) {
				$formControl = json_decode($input['description']);
				if(array_key_exists('after', $formControl)) {
					$orders[$input['name']] = $orders[$formControl->after] - 10;
				}
			}

			$form->add(array(
				'name' => $input['name'],
				'type' => $this->guessType($input['name']),
				'options' => array(
					'label' => $formControl->label,
				),
				'attributes' => array(
					'class' => 'form-control '.$classes,
					'placeholder' => 'Bitte ausfüllen',
					'id' => $this->prefix . $input['name'],
					//kjs
					'data-bind' => $this->makeDataBind($formControl, $input),
				)
			), array(
				'priority' => $orders[$input['name']]
			));
		}

		$form->add(array(
			'name' => 'id',
			'type' => 'hidden',
			'attributes' => array(
				'id' => 'id',
				'class' => 'btn btn-primary',
				'data-bind' => 'click:save',
			),
		));


		$form->add(array(
			'name' => 'submit',
			'type' => 'Submit',
			'attributes' => array(
				'value' => 'Goo',
				'id' => 'submitbutton',
				'class' => 'btn btn-primary save',
				'data-bind' => 'click:save',
			),
		));

		$form->add(array(
			'name' => 'update',
			'type' => 'submit',
			'attributes' => array(
				'value' => 'Bearbeiten',
				'class' => 'btn btn-primary update',
				'data-bind' => 'click:updateFunction',
				'style' => 'display:none'
			),
		));

		$form->add(array(
			'name' => 'abort',
			'type' => 'Submit',
			'attributes' => array(
				'value' => 'Abbrechen',
				'class' => 'btn btn-primary cancel',
				'data-bind' => 'click:cancel',
				'style' => 'display:none'
			),
		));

		return $form;
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
	 * @param array $input
	 * @return string
	 */
	private function makeDataBind($formControl, array $input) {
		$dataBind = '';

		if(property_exists($formControl, 'show')) {
			if($formControl->show->type == 'select') {
				$kjsVar = 'show' . $formControl->show->relatedElement . $formControl->show->value;
				$this->dataBindArray['visible'] = 'visible: show' . $formControl->show->relatedElement . $formControl->show->value;
				$dataBind .= $this->dataBindArray['visible'] . ', ';
				$this->jsConfig['kjsVar'][] = array(
					'var' => $kjsVar,
					'type' => $formControl->show->type,
					'relatedElement' => $formControl->show->relatedElement,
					'init' => false,
					'showOnValue' => $formControl->show->value
				);
			}
		}

		$dataBind .= 'value:' . $input['name'];
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
}