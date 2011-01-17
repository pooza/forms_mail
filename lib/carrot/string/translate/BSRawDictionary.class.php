<?php
/**
 * @package org.carrot-framework
 * @subpackage string.translate
 */

/**
 * 翻訳を行わない辞書
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSRawDictionary.class.php 1812 2010-02-03 15:15:09Z pooza $
 */
class BSRawDictionary implements BSDictionary {

	/**
	 * 翻訳して返す
	 *
	 * @access public
	 * @param string $label ラベル
	 * @param string $language 言語
	 * @return string 翻訳された文字列
	 */
	public function translate ($label, $language) {
		return $label;
	}

	/**
	 * 辞書の名前を返す
	 *
	 * @access public
	 * @return string 辞書の名前
	 */
	public function getDictionaryName () {
		return get_class($this);
	}
}

/* vim:set tabstop=4: */
