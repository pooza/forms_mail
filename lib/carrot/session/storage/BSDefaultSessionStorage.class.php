<?php
/**
 * @package org.carrot-framework
 * @subpackage session.storage
 */

/**
 * 規定セッションストレージ
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 */
class BSDefaultSessionStorage implements BSSessionStorage {

	/**
	 * 初期化
	 *
	 * @access public
	 * @return string 利用可能ならTrue
	 */
	public function initialize () {
		ini_set('session.save_handler', 'files');
		ini_set('session.save_path', BSFileUtility::getPath('tmp'));
		return true;
	}
}

/* vim:set tabstop=4: */
