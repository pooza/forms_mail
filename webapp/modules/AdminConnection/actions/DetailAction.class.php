<?php
/**
 * Detailアクション
 *
 * @package jp.co.commons.forms.mail
 * @subpackage AdminConnection
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 */
class DetailAction extends BSRecordAction {

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
			'basicauth_uid' => $this->request['basicauth_uid'],
			'basicauth_password' => $this->request['basicauth_password'],
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
		return $this->redirect();
	}

	public function getDefaultView () {
		if (!$this->request['submit']) {
			$this->request['basicauth_password'] = $this->getRecord()->getPlainTextPassword();
		}
		$this->request->setAttribute('fields', $this->getRecord()->getRemoteFields());
		return BSView::INPUT;
	}

	public function handleError () {
		return $this->getDefaultView();
	}
}

/* vim:set tabstop=4: */
