<?php
/**
 * Createアクション
 *
 * @package jp.co.b-shock.forms.mail
 * @subpackage AgentRecipient
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 */
class CreateAction extends BSAction {
	private $connection;

	private function getConnection () {
		if (!$this->connection) {
			$connections = new ConnectionHandler;
			$this->connection = $connections->getRecord(array(
				'emptymail_email' => $this->request['to'],
			));
		}
		return $this->connection;
	}

	public function execute () {
		try {
			$this->database->beginTransaction();
			if (!$connection = $this->getConnection()) {
				throw new RuntimeException('該当する接続がありません。');
			}
			$connection->activateRecipient(
				BSMailAddress::create($this->request['from'])
			);
			$this->database->commit();
		} catch (Exception $e) {
			$this->database->rollBack();
			$this->request->setError($this->getTable()->getName(), $e->getMessage());
			return $this->handleError();
		}
		return BSView::SUCCESS;
	}

	public function handleError () {
		BSLogManager::getInstance()->put(
			$this->request->getErrors()->join(':', '/'),
			$this->getModule()->getName()
		);
		return BSView::ERROR;
	}
}

/* vim:set tabstop=4: */
