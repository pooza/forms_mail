<?php
/**
 * @package jo.co.commons.forms.mail
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
	 * 親レコードを返す
	 *
	 * @access public
	 * @return BSRecord 親レコード
	 */
	public function getParent () {
		return $this->getConnection();
	}
}

/* vim:set tabstop=4 */