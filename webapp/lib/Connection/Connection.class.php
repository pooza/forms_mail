<?php
/**
 * @package jp.co.commons.forms.mail
 */

/**
 * 接続レコード
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 */
class Connection extends BSSortableRecord {
	private $articles;
	private $recipients;

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
	 * 記事を返す
	 *
	 * @access public
	 * @return ArticleHandler 記事
	 */
	public function getLogs () {
		if (!$this->articles) {
			$this->articles = new ArticleHandler;
			$this->articles->getCriteria()->register('connection_id', $this);
		}
		return $this->articles;
	}

	/**
	 * 受取人を返す
	 *
	 * @access public
	 * @return RecipientHandler 受取人
	 */
	public function getRecipients () {
		if (!$this->recipients) {
			$this->recipients = new RecipientHandler;
			$this->recipients->getCriteria()->register('connection_id', $this);
		}
		return $this->recipients;
	}

	/**
	 * ラベルを返す
	 *
	 * @access public
	 * @param string $language 言語
	 * @return string ラベル
	 */
	public function getLabel ($language = 'ja') {
		return $this['url'];
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