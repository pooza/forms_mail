<?php
/**
 * @package jp.co.b-shock.forms.mail
 */

/**
 * 受取人レコード
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 */
class Recipient extends BSRecord {

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
	 * メールアドレスを返す
	 *
	 * @access public
	 * @return BSMailAddress メールアドレス
	 */
	public function getMailAddress () {
		return BSMailAddress::create($this['email']);
	}

	/**
	 * アクティブ化
	 *
	 * @access public
	 * @return BSMailAddress メールアドレス
	 */
	public function activate () {
		if ($this['status'] == 'banned') {
			throw new BSException('banned. ' . $this->getMailAddress()->getContents());
		}
		$this->update(array('status' => 'active'));
	}

	/**
	 * 非アクティブ化
	 *
	 * @access public
	 * @return BSMailAddress メールアドレス
	 */
	public function inactivate () {
		$this->update(array('status' => 'inactive'));
	}

	/**
	 * ラベルを返す
	 *
	 * @access public
	 * @param string $language 言語
	 * @return string ラベル
	 */
	public function getLabel ($language = 'ja') {
		return $this['email'];
	}
}

/* vim:set tabstop=4 */