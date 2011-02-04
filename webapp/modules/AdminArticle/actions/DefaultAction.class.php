<?php
/**
 * Defaultアクション
 *
 * @package jp.co.commons.forms.mail
 * @subpackage AdminArtile
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 */
class DefaultAction extends BSAction {
	public function execute () {
		return $this->getModule()->getAction('List')->forward();
	}
}

/* vim:set tabstop=4: */
