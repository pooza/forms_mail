<?php
/**
 * BackupDatabaseアクション
 *
 * @package org.carrot-framework
 * @subpackage Console
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BackupDatabaseAction.class.php 1812 2010-02-03 15:15:09Z pooza $
 */
class BackupDatabaseAction extends BSAction {
	private $database;

	/**
	 * 対象データベースを返す
	 *
	 * @access private
	 * @return BSDatabase 対象データベース
	 */
	private function getDatabase () {
		if (!$this->database) {
			if (!$name = $this->request['d']) {
				$name = 'default';
			}
			$this->database = BSDatabase::getInstance($name);
		}
		return $this->database;
	}

	public function initialize () {
		$this->request->addOption('d');
		$this->request->parse();
		return true;
	}

	public function execute () {
		try {
			$this->getDatabase()->createDumpFile();
		} catch (Exception $e) {
			$this->handleError();
		}
		return BSView::NONE;
	}

	public function handleError () {
		return BSView::NONE;
	}

	public function validate () {
		return !!$this->getDatabase();
	}
}

/* vim:set tabstop=4: */
