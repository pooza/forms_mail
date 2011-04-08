<?php
/**
 * Resignアクション
 *
 * @package jp.co.commons.forms.mail
 * @subpackage UserConnection
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 */
class ResignAction extends BSRecordAction {
	private $recipient;

	private function getRecipient () {
		if (!$this->recipient) {
			$this->recipient = $this->getRecord()->getRecipient(
				BSMailAddress::create($this->request['email'])
			);
		}
		return $this->recipient;
	}

	public function execute () {
		try {
			$this->database->beginTransaction();
			$this->getRecipient()->delete();
			$this->database->commit();
		} catch (Exception $e) {
			$this->database->rollBack();
			$this->request->setError($this->getTable()->getName(), '解除に失敗しました。');
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

	public function validate () {
		if ($this->request['email'] && !$this->getRecipient()) {
			$this->request->setError('email', '登録されていません。');
		}
		return parent::validate();
	}
}

/* vim:set tabstop=4: */
