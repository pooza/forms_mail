<?php
/**
 * @package org.carrot-framework
 * @subpackage xml.xhtml.object
 */

/**
 * FlashLight用object要素
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSFlashLightObjectElement.class.php 2144 2010-06-15 02:59:43Z pooza $
 */
class BSFlashLightObjectElement extends BSFlashObjectElement {

	/**
	 * @access public
	 * @param string $name 要素の名前
	 * @param BSUserAgent $useragent 対象UserAgent
	 */
	public function __construct ($name = null, BSUserAgent $useragent = null) {
		BSXHTMLElement::__construct($name, $useragent);
		$this->setAttribute('type', BSMIMEType::getType('swf'));
	}

	/**
	 * 内容をXMLで返す
	 *
	 * @access public
	 * @return string XML要素
	 */
	public function getContents () {
		return BSXHTMLElement::getContents();
	}
}

/* vim:set tabstop=4: */
