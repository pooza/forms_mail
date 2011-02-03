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
	 * 更新
	 *
	 * @access public
	 * @param mixed $values 更新する値
	 * @param integer $flags フラグのビット列
	 *   BSDatabase::WITHOUT_LOGGING ログを残さない
	 *   BSDatabase::WITHOUT_SERIALIZE シリアライズしない
	 */
	public function update ($values, $flags = null) {
		$values = new BSArray($values);
		if (!BSString::isBlank($values['password'])) {
			$values['password'] = BSCrypt::getInstance()->encrypt($values['password']);
		}
		parent::update($values, $flags);
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
	 * リモートフィールドを返す
	 *
	 * @access public
	 * @return BSArray リモートフィールドの配列を返す
	 */
	public function getRemoteFields () {
		if (!$this->remoteFields) {
			$this->remoteFields = new BSArray;
			foreach ($this->fetchRemoteFields() as $field) {
				$field = new BSArray($field);
				if (!!$field['choices']) {
					$field['choices'] = new BSArray($field['choices']);
					$this->remoteFields[$field['name']] = $field;
				}
			}
		}
		return $this->remoteFields;
	}

	private function fetchRemoteFields () {
		try {
			$url = $this->getURL();
			$service = new BSCurlHTTP($url['host']);
			$service->setAuth($this['uid'], $this['password']);
			$response = $service->sendGET($url->getFullPath());
			$serializer = new BSJSONSerializer;
			$data = $serializer->decode($response->getRenderer()->getContents());
			return $data['fields'];
		} catch (Exception $e) {
			return array();
		}
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

	/**
	 * 全てのファイル属性
	 *
	 * @access protected
	 * @return BSArray ファイル属性の配列
	 */
	protected function getFullAttributes () {
		$values = parent::getFullAttributes();
		$values['password_plaintext'] = BSCrypt::getInstance()->decrypt($values['password']);
		return $values;
	}
}

/* vim:set tabstop=4 */