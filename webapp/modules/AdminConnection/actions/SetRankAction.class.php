<?php
/**
 * SetRankアクション
 *
 * @package jp.co.commons.forms.mail
 * @subpackage AdminConnection
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 */
class SetRankAction extends BSRecordAction {
	public function execute () {
		try {
			$this->database->beginTransaction();
			$this->getRecord()->setOrder($this->request['option']);
			$this->database->commit();
		} catch (Exception $e) {
			$this->database->rollBack();
			$this->request->setError($this->getTable()->getName(), $e->getMessage());
			return $this->handleError();
		}

		return $this->getModule()->redirect();
	}

	public function handleError () {
		return $this->controller->getAction('not_found')->forward();
	}
}

/* vim:set tabstop=4: */
