<?php
/**
 * Listアクション
 *
 * @package jp.co.b-shock.forms.mail
 * @subpackage AdminConnection
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 */
class ListAction extends BSTableAction {
	public function execute () {
		$this->request->setAttribute('connections', $this->getRows());
		return BSView::INPUT;
	}
}

/* vim:set tabstop=4: */
