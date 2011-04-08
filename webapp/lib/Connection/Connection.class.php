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
	 * 受取人を登録
	 *
	 * @access public
	 * @param BSMailAddress $email メールアドレス
	 * @final
	 */
	final public function join (BSMailAddress $email) {
		$this->registerRecipient($email);
	}

	/**
	 * 受取人を返す
	 *
	 * @access public
	 * @param BSMailAddress $email メールアドレス
	 */
	public function getRecipient (BSMailAddress $email) {
		$recipients = clone $this->getRecipients();
		$recipients->getCriteria()->register('email', $email->getContents());
		$recipients->getCriteria()->register('status', 'banned', '<>');
		return $recipients->getIterator()->getFirst();
	}

	/**
	 * リモートフィールドを返す
	 *
	 * @access public
	 * @return BSArray リモートフィールドの配列を返す
	 */
	public function getRemoteFields () {
		if (!$this->remoteFields && !BSString::isBlank($url = $this['fields_url'])) {
			$this->remoteFields = ConnectionHandler::fetchRemoteFields(BSURL::create($url));
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

	/**
	 * 全てのファイル属性
	 *
	 * @access protected
	 * @return BSArray ファイル属性の配列
	 */
	protected function getFullAttributes () {
		$values = parent::getFullAttributes();
		foreach (array('join', 'resign') as $action) {
			$url = BSURL::create(null, 'carrot');
			$url['module'] = 'UserConnection';
			$url['action'] = BSString::pascalize($action);
			$url['record'] = $this;
			$values[$action . '_url'] = $url->getContents();
		}
		return $values;
	}
}

/* vim:set tabstop=4 */