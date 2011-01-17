<?php
/**
 * DatabaseListアクション
 *
 * @package org.carrot-framework
 * @subpackage DevelopTableReport
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: DatabaseListAction.class.php 1812 2010-02-03 15:15:09Z pooza $
 */
class DatabaseListAction extends BSAction {
	public function execute () {
		$this->request->setAttribute('databases', BSDatabase::getDatabases());
		return BSView::SUCCESS;
	}
}

/* vim:set tabstop=4: */
