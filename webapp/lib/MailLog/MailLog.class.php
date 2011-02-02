<?php
/**
 * @package jo.co.commons.forms.mail
 */

/**
 * メールログレコード
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 */
class MailLog extends BSRecord {

	/**
	 * 親レコードを返す
	 *
	 * @access public
	 * @return BSRecord 親レコード
	 */
	public function getParent () {
		return $this->getArticle();
	}
}

/* vim:set tabstop=4 */