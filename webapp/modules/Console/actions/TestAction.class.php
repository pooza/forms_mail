<?php
/**
 * Testアクション
 *
 * @package org.carrot-framework
 * @subpackage Console
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: TestAction.class.php 2269 2010-08-10 15:39:02Z pooza $
 */
class TestAction extends BSAction {
	public function execute () {
		BSTestManager::getInstance()->execute();
	}
}

/* vim:set tabstop=4: */
