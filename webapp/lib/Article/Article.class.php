<?php
/**
 * @package jp.co.b-shock.forms.mail
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
		foreach (array('body', 'body_mobile') as $field) {
			$file = BSFileUtility::createTemporaryFile();
			$file->setContents($this[$field]);
			$this->setAttachment($file, $field . '_template', 'filename.tpl');
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
	 * 送信ログを記録
	 *
	 * @access public
	 * @param BSMailAddress $email 宛先
	 */
	public function log (BSMailAddress $email) {
		$this->getLogs()->createRecord(array(
			'article_id' => $this->getID(),
			'email' => $email->getContents(),
		));
	}

	/**
	 * ユーザー宛てに送信済みか？
	 *
	 * @access public
	 * @param BSMailAddress $email 宛先
	 * @return boolean 送信済みならTrue
	 */
	public function isSentTo (BSMailAddress $email) {
		return !!$this->getLogs()->getRecord(array(
			'article_id' => $this->getID(),
			'email' => $email->getContents(),
		));
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
	 * 配信
	 *
	 * @access public
	 */
	public function publish () {
		$connection = $this->getConnection();
		if ($connection['fields_url'] && $connection['members_url']) {
			//未実装
		}
		if ($connection['emptymail_email']) {
			$recipients = clone $this->getConnection()->getRecipients();
			$recipients->getCriteria()->register('status', 'active');
			foreach ($recipients as $recipient) {
				$this->sendTo($recipient);
			}
		}
		$this->update(array('is_published' => 1));
		BSLogManager::getInstance()->put($this . 'を配信しました。', $this);
	}

	/**
	 * ユーザー宛てに送信
	 *
	 * @access public
	 * @param BSRecord $recipient 対象レコード
	 */
	public function sendTo (BSRecord $recipient) {
		try {
			if ($this->isSentTo($email = $recipient->getMailAddress())) {
				throw new Exception($this . 'は' . $email . 'に送信済みです。');
			}

			$mail = new BSSmartyMail;
			$mail->getRenderer()->setTemplate('Article.mail');
			$params = $this->getAssignableValues();
			if ($email->isMobile() && !BSString::isBlank($this['body_mobile'])) {
				$params['body_template'] = $this->getAttachmentInfo('body_mobile_template');
			}
			$mail->getRenderer()->setAttribute('article', $params);
			$mail->getRenderer()->setAttribute('connection', $this->getConnection());
			$mail->getRenderer()->setAttribute('recipient', $recipient);
			$mail->send();
			$this->log($email);
		} catch (Exception $e) {
			BSLogManager::getInstance()->put($e->getMessage(), $this);
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