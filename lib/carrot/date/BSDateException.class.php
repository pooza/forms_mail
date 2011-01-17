<?php
/**
 * @package org.carrot-framework
 * @subpackage date
 */

/**
 * 日付例外
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSDateException.class.php 1812 2010-02-03 15:15:09Z pooza $
 */
class BSDateException extends BSException {

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
