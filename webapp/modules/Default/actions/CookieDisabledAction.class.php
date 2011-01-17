<?php
/**
 * CookieDisabledアクション
 *
 * @package org.carrot-framework
 * @subpackage Default
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: CookieDisabledAction.class.php 1887 2010-02-28 05:07:20Z pooza $
 */
class CookieDisabledAction extends BSAction {
	public function execute () {
		return BSView::ERROR;
	}
}

/* vim:set tabstop=4: */
