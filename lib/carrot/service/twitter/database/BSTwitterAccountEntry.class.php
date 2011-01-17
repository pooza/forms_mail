<?php
/**
 * @package org.carrot-framework
 * @subpackage service.twitter.database
 */

/**
 * Twitterアカウント エントリーレコード
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSTwitterAccountEntry.class.php 2065 2010-05-04 10:54:17Z pooza $
 */
class BSTwitterAccountEntry extends BSRecord {

	/**
	 * 削除可能か？
	 *
	 * @access protected
	 * @return boolean 削除可能ならTrue
	 */
	protected function isDeletable () {
		return true;
	}
}

/* vim:set tabstop=4: */
