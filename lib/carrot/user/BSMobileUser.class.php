<?php
/**
 * @package org.carrot-framework
 * @subpackage user
 */

/**
 * ケータイユーザー
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSMobileUser.class.php 1985 2010-04-11 02:18:21Z pooza $
 */
class BSMobileUser extends BSUser {

	/**
	 * ログイン
	 *
	 * @access public
	 * @param BSUserIdentifier $id ユーザーIDを含んだオブジェクト
	 * @param string $password パスワード
	 * @return boolean 成功ならTrue
	 */
	public function login (BSUserIdentifier $identifier = null, $password = null) {
		if (!$identifier) {
			$identifier = BSRequest::getInstance()->getUserAgent();
		}
		return parent::login($identifier, $password);
	}
}

/* vim:set tabstop=4: */
