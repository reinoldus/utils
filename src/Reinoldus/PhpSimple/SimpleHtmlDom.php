<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 07.01.15
 * Time: 14:09
 */

namespace Reinoldus\PhpSimple;

use Reinoldus\PhpSimple\Exception\DomNotLoaded;
use Reinoldus\PhpSimple\Exception\ElementNotFound;

require_once('simplehtmldom_1_5'.DIRECTORY_SEPARATOR.'simple_html_dom.php');
require_once('functions'.DIRECTORY_SEPARATOR.'cast.php');

/**
 * Class SimpleHtmlDom
 * @package Reinoldus\PhpSimple
 * @parameter innertext
 */
class SimpleHtmlDom extends \simple_html_dom {

	/**
	 * @param $url
	 * @param bool $use_include_path
	 * @param null $context
	 * @param int $offset
	 * @param int $maxLen
	 * @param bool $lowercase
	 * @param bool $forceTagsClosed
	 * @param string $target_charset
	 * @param bool $stripRN
	 * @param string $defaultBRText
	 * @param string $defaultSpanText
	 * @return SimpleHtmlDom
	 * @throws DomNotLoaded
	 * @deprecated
	 */
	static public function file_get_html($url, $use_include_path = false, $context=null, $offset = -1, $maxLen=-1, $lowercase = true, $forceTagsClosed=true, $target_charset = DEFAULT_TARGET_CHARSET, $stripRN=true, $defaultBRText=DEFAULT_BR_TEXT, $defaultSpanText=DEFAULT_SPAN_TEXT) {
		// We DO force the tags to be terminated.
		$dom = new SimpleHtmlDom(null, $lowercase, $forceTagsClosed, $target_charset, $stripRN, $defaultBRText, $defaultSpanText);
		// For sourceforge users: uncomment the next line and comment the retreive_url_contents line 2 lines down if it is not already done.
		$contents = file_get_contents($url, $use_include_path, $context, $offset);
		// Paperg - use our own mechanism for getting the contents as we want to control the timeout.
		//$contents = retrieve_url_contents($url);
		if (empty($contents) || strlen($contents) > MAX_FILE_SIZE)
		{
			throw new DomNotLoaded();
		}
		// The second parameter can force the selectors to all be lowercase.
		$dom->load($contents, $lowercase, $stripRN);
		return $dom;
	}

	/**
	 * @param $str
	 * @param bool $lowercase
	 * @param bool $forceTagsClosed
	 * @param string $target_charset
	 * @param bool $stripRN
	 * @param string $defaultBRText
	 * @param string $defaultSpanText
	 * @return SimpleHtmlDom
	 * @throws DomNotLoaded
	 */
	static public function str_get_html($str, $lowercase=true, $forceTagsClosed=true, $target_charset = DEFAULT_TARGET_CHARSET, $stripRN=true, $defaultBRText=DEFAULT_BR_TEXT, $defaultSpanText=DEFAULT_SPAN_TEXT)
	{
		$dom = new SimpleHtmlDom(null, $lowercase, $forceTagsClosed, $target_charset, $stripRN, $defaultBRText, $defaultSpanText);
		if (empty($str) || strlen($str) > MAX_FILE_SIZE)
		{
			$dom->clear();
			throw new DomNotLoaded();
		}
		$dom->load($str, $lowercase, $stripRN);
		return $dom;
	}

	/**
	 * @param $selector
	 * @param null $idx
	 * @param bool $lowercase
	 * @return SimpleHtmlDomNode|SimpleHtmlDomNode[]
	 * @throws ElementNotFound
	 */
	public function find($selector, $idx=null, $lowercase=false)
	{
		$result = parent::find($selector, $idx, $lowercase);

		if($result === NULL || (is_array($result) && empty($result))) {
			throw new ElementNotFound();
		}

		if(is_array($result)) {
			foreach($result as $key => $var) {
				//echo get_class($var);
				$result[$key] = cast("Reinoldus\PhpSimple\SimpleHtmlDomNode", $var);
			}
		} else {
			//echo get_class($result);
			$result = cast("Reinoldus\PhpSimple\SimpleHtmlDomNode", $result);
		}

		return $result;
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