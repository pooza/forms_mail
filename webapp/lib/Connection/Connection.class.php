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
	private $remoteFields;
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
		return !$this->getArticles()->count();
	}

	/**
	 * 記事を返す
	 *
	 * @access public
	 * @return ArticleHandler 記事
	 */
	public function getArticles () {
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
	 * 受取人を登録
	 *
	 * @access public
	 * @param BSMailAddress $email メールアドレス
	 */
	public function registerRecipient (BSMailAddress $email) {
		$values = new BSArray(array(
			'connection_id' => $this->getID(),
			'email' => $email->getContents(),
		));
		if ($recipient = $this->getRecipients()->getRecord($values)) {
			$recipient->activate();
		} else {
			$id = $this->getRecipients()->createRecord($values);
		}
		if (!BSString::isBlank($body = $this['emptymail_reply_body'])) {
			$mail = new BSSmartyMail;
			$mail->getRenderer()->setTemplate('Recipient.register.mail');
			$mail->getRenderer()->setAttribute('connection', $this);
			$mail->getRenderer()->setAttribute('recipient', $values);
			$mail->send();
		}
	}

	/**
	 * リモートフィールドを返す
	 *
	 * @access public
	 * @return BSArray リモートフィールドの配列を返す
	 */
	public function getRemoteFields () {
		if (!$this->remoteFields && !BSString::isBlank($url = $this['fields_url'])) {
			$this->remoteFields = ConnectionHandler::fetchRemoteFields(BSURL::getInstance($url));
		}
		return $this->remoteFields;
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