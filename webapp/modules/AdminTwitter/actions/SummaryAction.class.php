<?php
/**
 * Summaryアクション
 *
 * @package org.carrot-framework
 * @subpackage AdminTwitter
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: SummaryAction.class.php 2062 2010-05-04 10:30:47Z pooza $
 */
class SummaryAction extends BSAction {
	public function execute () {
		$account = BSAuthorRole::getInstance()->getTwitterAccount();
		if ($account->isAuthenticated()) {
			$this->request->setAttribute('account', $account);
		} else {
			$values = array('url' => $account->getOAuthURL()->getContents());
			$this->request->setAttribute('oauth', $values);
		}
		return BSView::SUCCESS;
	}
}

/* vim:set tabstop=4: */
