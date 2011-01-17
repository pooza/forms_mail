<?php
/**
 * @package org.carrot-framework
 * @subpackage js
 */

/**
 * JavaScriptユーティリティ
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSJavaScriptUtility.class.php 1812 2010-02-03 15:15:09Z pooza $
 */
class BSJavaScriptUtility {

	/**
	 * @access private
	 */
	private function __construct () {
	}

	/**
	 * script要素を返す
	 *
	 * @access public
	 * @return BSXMLElement script要素
	 * @static
	 */
	static public function getScriptElement () {
		return new BSScriptElement;
	}

	/**
	 * 文字列のクォート
	 *
	 * @access public
	 * @param string $value 置換対象
	 * @return string 置換結果
	 * @static
	 */
	static public function quote ($value) {
		$serializer = new BSJSONSerializer;
		return $serializer->encode($value);
	}
}

/* vim:set tabstop=4: */
