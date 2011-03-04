<?php
/**
 * @package jp.co.commons.forms.mail
 */

/**
 * メールログテーブル
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 */
class MailLogHandler extends BSTableHandler {

	/**
	 * レコード追加可能か？
	 *
	 * @access protected
	 * @return boolean レコード追加可能ならTrue
	 */
	protected function isInsertable () {
		return true;
	}

	/**
	 * レコード追加
	 *
	 * @access public
	 * @param mixed $values 値
	 * @param integer $flags フラグのビット列
	 *   BSDatabase::WITHOUT_LOGGING ログを残さない
	 * @return string レコードの主キー
	 */
	public function createRecord ($values, $flags = BSDatabase::WITHOUT_LOGGING) {
		return parent::createRecord($values, $flags);
	}
}

/* vim:set tabstop=4 */