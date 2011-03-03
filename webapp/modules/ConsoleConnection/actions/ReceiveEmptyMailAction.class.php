<?php
/**
 * ReceiveEmptyMailアクション
 *
 * @package jp.co.commons.forms.mail
 * @subpackage ConsoleConnection
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 */
class ReceiveEmptyMailAction extends BSAction {
	public function execute () {
		throw new BSDatabaseException(1);
	}
}

/* vim:set tabstop=4: */
