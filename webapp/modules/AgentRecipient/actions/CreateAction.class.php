<?php
/**
 * Createアクション
 *
 * @package jp.co.commons.forms.mail
 * @subpackage AgentRecipient
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 */
class CreateAction extends BSAction {
	public function execute () {
		BSLogManager::getInstance()->put($this->request['from']);
		BSLogManager::getInstance()->put($this->request['to']);
		return BSView::SUCCESS;
	}

	public function handleError () {
		BSLogManager::getInstance()->put('err');
		return BSView::ERROR;
	}
}

/* vim:set tabstop=4: */
