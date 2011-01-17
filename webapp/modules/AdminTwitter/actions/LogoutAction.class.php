<?php
/**
 * Logoutアクション
 *
 * @package org.carrot-framework
 * @subpackage AdminTwitter
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: LogoutAction.class.php 2059 2010-05-04 07:45:36Z pooza $
 */
class LogoutAction extends BSAction {
	public function execute () {
		$account = BSAuthorRole::getInstance()->getTwitterAccount();
		$account->logout();
		return $this->getModule()->redirect();
	}
}

/* vim:set tabstop=4: */
