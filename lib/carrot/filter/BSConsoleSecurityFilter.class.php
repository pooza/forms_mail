<?php
/**
 * @package org.carrot-framework
 * @subpackage filter
 */

/**
 * コンソール認証
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSConsoleSecurityFilter.class.php 1920 2010-03-21 09:16:06Z pooza $
 */
class BSConsoleSecurityFilter extends BSFilter {
	public function execute () {
		if (!BSString::isBlank($user = $this->controller->getAttribute('USER'))) {
			if (!BSProcess::getAllowedUsers()->isContain($user)) {
				$message = new BSString('実行ユーザー "%s" が正しくありません。');
				$message[] = $user;
				throw new BSConsoleException($message);
			}
		}
		return !$this->request->isCLI();
	}
}

/* vim:set tabstop=4: */
