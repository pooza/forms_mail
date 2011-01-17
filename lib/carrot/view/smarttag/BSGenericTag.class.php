<?php
/**
 * @package org.carrot-framework
 * @subpackage view.smarttag
 */

/**
 * 一般タグ
 *
 * あらゆるスマートタグにマッチし、変換せずにそのまま出力する。
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSGenericTag.class.php 2053 2010-05-04 04:36:57Z pooza $
 */
class BSGenericTag extends BSSmartTag {

	/**
	 * タグ名を返す
	 *
	 * @access public
	 * @return string タグ名
	 */
	public function getTagName () {
		return null;
	}

	/**
	 * 一致するか
	 *
	 * @access public
	 * @return boolean 一致するならTrue
	 */
	public function isMatched () {
		return true;
	}

	/**
	 * 置換して返す
	 *
	 * @access public
	 * @param string $body 置換対象文字列
	 * @return string 置換された文字列
	 */
	public function execute ($body) {
		return str_replace($this->getContents(), $this->tag[1], $body);
	}
}

/* vim:set tabstop=4: */
