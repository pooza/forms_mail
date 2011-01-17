<?php
/**
 * @package org.carrot-framework
 * @subpackage xml.xhtml
 */

/**
 * hr要素
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSHorizontalRuleElement.class.php 1812 2010-02-03 15:15:09Z pooza $
 */
class BSHorizontalRuleElement extends BSXHTMLElement {

	/**
	 * タグ名を返す
	 *
	 * @access public
	 * @return string タグ名
	 */
	public function getTag () {
		return 'hr';
	}

	/**
	 * 空要素か？
	 *
	 * @access public
	 * @return boolean 空要素ならTrue
	 */
	public function isEmptyElement () {
		return true;
	}
}

/* vim:set tabstop=4: */
