<?php
/**
 * DeniedUserAgentアクション
 *
 * @package org.carrot-framework
 * @subpackage Default
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: DeniedUserAgentAction.class.php 1812 2010-02-03 15:15:09Z pooza $
 */
class DeniedUserAgentAction extends BSAction {
	public function execute () {
		return BSView::ERROR;
	}
}

/* vim:set tabstop=4: */
