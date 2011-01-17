<?php
/**
 * Optimizeアクション
 *
 * @package org.carrot-framework
 * @subpackage DevelopTableReport
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: OptimizeAction.class.php 1834 2010-02-07 10:57:08Z pooza $
 */
class OptimizeAction extends BSAction {
	private $database;

	private function getDatabase () {
		if (!$this->database) {
			$this->database = BSDatabase::getInstance($this->request['database']);
		}
		return $this->database;
	}

	public function execute () {
		$this->getDatabase()->optimize();

		$url = $this->getModule()->getAction('Database')->getURL();
		$url->setParameter('database', $this->getDatabase()->getName());
		return $url->redirect();
	}

	public function handleError () {
		return $this->controller->getAction('not_found')->forward();
	}

	public function validate () {
		return !!$this->getDatabase();
	}
}

/* vim:set tabstop=4: */
