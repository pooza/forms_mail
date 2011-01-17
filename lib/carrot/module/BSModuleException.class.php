<?php
/**
 * @package org.carrot-framework
 * @subpackage module
 */

/**
 * モジュール例外
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSModuleException.class.php 1812 2010-02-03 15:15:09Z pooza $
 */
class BSModuleException extends BSException {

	/**
	 * ログを書き込むか
	 *
	 * ループが発生する為、ログは書き込まない。
	 *
	 * @access public
	 * @return boolean ログを書き込むならTrue
	 */
	public function isLoggable () {
		return false;
	}
}

/* vim:set tabstop=4: */
