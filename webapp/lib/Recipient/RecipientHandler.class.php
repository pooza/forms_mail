<?php
/**
 * @package jp.co.commons.forms.mail
 */

/**
 * 受取人テーブル
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 */
class RecipientHandler extends BSTableHandler {

	/**
	 * レコード追加可能か？
	 *
	 * @access protected
	 * @return boolean レコード追加可能ならTrue
	 */
	protected function isInsertable () {
		return true;
	}
}

/* vim:set tabstop=4 */