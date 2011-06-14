<?php
/**
 * Createアクション
 *
 * @package jp.co.b-shock.forms.mail
 * @subpackage AdminConnection
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 */
class CreateAction extends BSRecordAction {

	/**
	 * レコードのフィールド値を配列で返す
	 *
	 * @access protected
	 * @return mixed[] フィールド値の連想配列
	 */
	protected function getRecordValues () {
		return array(
			'name' => $this->request['name'],
			'fields_url' => $this->request['fields_url'],
			'members_url' => $this->request['members_url'],
			'sender_email' => $this->request['sender_email'],
			'emptymail_email' => $this->request['emptymail_email'],
			'emptymail_reply_body' => $this->request['emptymail_reply_body'],
		);
	}

	public function execute () {
		try {
			$this->database->beginTransaction();
			$this->updateRecord();
			$this->database->commit();
		} catch (Exception $e) {
			$this->database->rollBack();
			$this->request->setError($this->getTable()->getName(), $e->getMessage());
			return $this->handleError();
		}
		return $this->getModule()->getAction('Detail')->redirect();
	}

	public function getDefaultView () {
		return BSView::INPUT;
	}

	public function handleError () {
		return $this->getDefaultView();
	}
}

/* vim:set tabstop=4: */
