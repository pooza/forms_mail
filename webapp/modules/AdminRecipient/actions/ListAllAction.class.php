<?php
/**
 * ListAllアクション
 *
 * @package jp.co.b-shock.forms.mail
 * @subpackage AdminArtile
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 */
class ListAllAction extends BSAction {
	public function execute () {
		$this->getModule()->clearParameterCache();
		return BSView::SUCCESS;
	}
}

/* vim:set tabstop=4: */
