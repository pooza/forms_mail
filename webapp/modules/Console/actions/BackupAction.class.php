<?php
/**
 * Backupアクション
 *
 * @package org.carrot-framework
 * @subpackage Console
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BackupAction.class.php 1812 2010-02-03 15:15:09Z pooza $
 */
class BackupAction extends BSAction {
	public function execute () {
		BSBackupManager::getInstance()->execute();
		return BSView::NONE;
	}
}

/* vim:set tabstop=4: */
