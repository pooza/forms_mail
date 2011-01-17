<?php
/**
 * @package org.carrot-framework
 * @subpackage log.logger.database
 */

/**
 * ログレコード
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSLogEntry.class.php 1812 2010-02-03 15:15:09Z pooza $
 */
class BSLogEntry extends BSRecord {

	/**
	 * 例外か？
	 *
	 * @access public
	 * @return boolean 例外ならTrue
	 */
	public function isException () {
		return mb_ereg('Exception$', $this->getAttribute('priority'));
	}
}
