<?php
/**
 * @package org.carrot-framework
 * @subpackage js
 */

/**
 * JavaScriptのURL
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSJavaScriptURL.class.php 2328 2010-09-06 10:24:00Z pooza $
 */
class BSJavaScriptURL extends BSURL {

	/**
	 * 内容を返す
	 *
	 * @access public
	 * @return string URL
	 */
	public function getContents () {
		if (!$this->contents) {
			$this->contents = $this['scheme'] . ':' . $this['path'];
		}
		return $this->contents;
	}

	/**
	 * 属性を設定
	 *
	 * @access public
	 * @param string $name 属性の名前
	 * @param mixed $value 値
	 * @return BSURL 自分自身
	 */
	public function setAttribute ($name, $value) {
		switch ($name) {
			case 'scheme':
			case 'path':
				$this->attributes[$name] = $value;
				break;
		}
		$this->contents = null;
		return $this;
	}
}

/* vim:set tabstop=4: */
