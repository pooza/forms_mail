<?php
/**
 * @package jp.co.commons.forms.mail
 */

/**
 * 記事レコード
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 */
class Article extends BSRecord {
	private $logs;

	/**
	 * 更新可能か？
	 *
	 * @access protected
	 * @return boolean 更新可能ならTrue
	 */
	protected function isUpdatable () {
		return true;
	}

	/**
	 * 削除可能か？
	 *
	 * @access protected
	 * @return boolean 削除可能ならTrue
	 */
	protected function isDeletable () {
		return true;
	}

	/**
	 * 親レコードを返す
	 *
	 * @access public
	 * @return BSRecord 親レコード
	 */
	public function getParent () {
		return $this->getConnection();
	}

	/**
	 * メールログを返す
	 *
	 * @access public
	 * @return MailLogHandler メールログ
	 */
	public function getLogs () {
		if (!$this->logs) {
			$this->logs = new MailLogHandler;
			$this->logs->getCriteria()->register('article_id', $this);
		}
		return $this->logs;
	}

	/**
	 * メールログを返す
	 *
	 * @access public
	 * @return MailLogHandler メールログ
	 * @final
	 */
	final public function getMailLogs () {
		return $this->getLogs();
	}

	/**
	 * シリアライズするか？
	 *
	 * @access public
	 * @return boolean シリアライズするならTrue
	 */
	public function isSerializable () {
		return true;
	}
}

/* vim:set tabstop=4 */