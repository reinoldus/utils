<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 07.01.15
 * Time: 14:09
 */

namespace Reinoldus\PhpSimple;

use Reinoldus\PhpSimple\Exception\ElementNotFound;

require_once('simplehtmldom_1_5'.DIRECTORY_SEPARATOR.'simple_html_dom.php');
require_once('functions'.DIRECTORY_SEPARATOR.'cast.php');

class SimpleHtmlDomNode extends \simple_html_dom_node {

	/**
	 * @param $selector
	 * @param null $idx
	 * @param bool $lowercase
	 * @return SimpleHtmlDomNode|SimpleHtmlDomNode[]
	 * @throws ElementNotFound
	 */
	public function find($selector, $idx=null, $lowercase=false) {
		$result = parent::find($selector, $idx, $lowercase);

		if($result === NULL || (is_array($result) && empty($result))) {
			throw new ElementNotFound();
		}

		if(is_array($result)) {
			foreach($result as $key => $var) {
				//echo get_class($var);
				//$result[$key] = new SimpleHtmlDomNode($var);
				$result[$key] = cast("Reinoldus\PhpSimple\SimpleHtmlDomNode", $var);
			}
		} else {
			//echo get_class($result);
			//$result = new SimpleHtmlDomNode($result);
			$result = cast("Reinoldus\PhpSimple\SimpleHtmlDomNode", $result);
		}

		return $result;
	}

	/**
	 * @return SimpleHtmlDomNode
	 * @throws ElementNotFound
	 */
	public function first_child() {
		$result = parent::first_child();

		if($result === NULL) {
			throw new ElementNotFound();
		}

		//return new SimpleHtmlDomNode($result);
		return cast("Reinoldus\PhpSimple\SimpleHtmlDomNode", $result);
	}

	/**
	 * DANGEROUS IMPLEMENTATION!!!
	 *
	 * @return SimpleHtmlDomNode
	 * @throws ElementNotFound
	 */
	public function next_sibling() {
		if ($this->parent===null)
		{
			return null;
		}

		$idx = 0;
		$count = count($this->parent->children);
		while ($idx<$count && $this->innertext !== $this->parent->children[$idx]->innertext)
		{
			++$idx;
		}
		if (++$idx>=$count)
		{
			throw new ElementNotFound();
		}

		return cast("Reinoldus\PhpSimple\SimpleHtmlDomNode", $this->parent->children[$idx]);
	}

	/**
	 * @param bool $show_attr
	 * @param int $deep
	 * @return string
	 */
	public function returnDump($show_attr=true, $deep=0)
	{
		$tree = "";
		$lead = str_repeat('....', $deep);

		$tree .= $lead.$this->tag;
		if ($show_attr && count($this->attr)>0)
		{
			$tree .= '(';
			foreach ($this->attr as $k=>$v)
				$tree .= "[$k]=>\"".$this->$k.'", ';
			$tree .= ')';
		}
		$tree .= "<br />\n";

		if ($this->nodes)
		{
			foreach ($this->nodes as $c)
			{
				$c = cast("Reinoldus\PhpSimple\SimpleHtmlDomNode", $c);
				$tree .= $c->returnDump($show_attr, $deep+1);
			}
		}

		return $tree;
	}
}