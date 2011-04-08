<?php
/**
 * Joinアクション
 *
 * @package jp.co.commons.forms.mail
 * @subpackage UserConnection
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 */
class JoinAction extends BSRecordAction {
	public function execute () {
		try {
			$this->database->beginTransaction();
			$this->getRecord()->registerRecipient(
				BSMailAddress::create($this->request['email'])
			);
			$this->database->commit();
		} catch (Exception $e) {
			$this->database->rollBack();
			$this->request->setError($this->getTable()->getName(), '登録に失敗しました。');
			return $this->handleError();
		}
		return BSView::SUCCESS;
	}

	public function getDefaultView () {
		return BSView::INPUT;
	}

	public function handleError () {
		if ($this->getRecord()) {
			return $this->getDefaultView();
		} else {
			return $this->controller->getAction('not_found');
		}
	}
}

/* vim:set tabstop=4: */
