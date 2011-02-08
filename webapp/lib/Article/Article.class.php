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
		return !$this->isPublished();
	}

	/**
	 * 更新
	 *
	 * @access public
	 * @param mixed $values 更新する値
	 * @param integer $flags フラグのビット列
	 *   BSDatabase::WITHOUT_LOGGING ログを残さない
	 *   BSDatabase::WITHOUT_SERIALIZE シリアライズしない
	 */
	public function update ($values, $flags = null) {
		parent::update($values, $flags);
		if (BSString::isBlank($this['body'])) {
			if ($file = $this->getAttachment('mail_template')) {
				$file->delete();
			}
		} else {
			$file = BSFileUtility::getTemporaryFile();
			$file->setContents($this['body']);
			$this->setAttachment($file, 'mail_template');
		}
	}

	/**
	 * 削除可能か？
	 *
	 * @access protected
	 * @return boolean 削除可能ならTrue
	 */
	protected function isDeletable () {
		return !$this->isPublished();
	}

	/**
	 * 発行済みか？
	 *
	 * @access public
	 * @public boolean 発行済みならTrue
	 */
	public function isPublished () {
		return !!$this['is_published'];
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
	 * テンプレートファイルを返す
	 *
	 * @access public
	 * @return BSTemplateFile テンプレートファイル
	 */
	public function getTemplate () {
		if ($file = $this->getAttachment('mail_template')) {
			return new BSTemplateFile($file->getPath());
		}
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