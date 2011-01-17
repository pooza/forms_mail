<?php
/**
 * @package org.carrot-framework
 * @subpackage string.translate
 */

/**
 * 翻訳例外
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSTranslateException.class.php 1953 2010-03-29 03:49:07Z pooza $
 */
class BSTranslateException extends BSException {

	/**
	 * ログを書き込むか
	 *
	 * @access public
	 * @return boolean ログを書き込むならTrue
	 */
	public function isLoggable () {
		return false;
	}
}

/* vim:set tabstop=4: */
