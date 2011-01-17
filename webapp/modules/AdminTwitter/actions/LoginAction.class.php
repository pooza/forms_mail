<?php
/**
 * Loginアクション
 *
 * @package org.carrot-framework
 * @subpackage AdminTwitter
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: LoginAction.class.php 2058 2010-05-04 07:31:07Z pooza $
 */
class LoginAction extends BSAction {
	public function execute () {
		$account = BSAuthorRole::getInstance()->getTwitterAccount();
		$account->login($this->request['verifier']);
		return $this->getModule()->redirect();
	}
}

/* vim:set tabstop=4: */
