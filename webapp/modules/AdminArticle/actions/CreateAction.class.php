<?php
/**
 * Createアクション
 *
 * @package jp.co.commons.forms.mail
 * @subpackage AdminArticle
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
		$fields = new BSArray($this->request['fields']);
		return array(
			'title' => $this->request['title'],
			'body' => $this->request['body'],
			'body_mobile' => $this->request['body_mobile'],
			'criteria' => $this->getModule()->serializeCriteria($fields),
			'publish_date' => $this->request['publish_date'],
			'connection_id' => $this->getModule()->getConnection()->getID(),
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
		if (!$this->request['submit']) {
			$date = BSDate::getNow()->setAttribute('day', '+1');
			$date->setHasTime(false);
			$this->request['publish_date'] = $date->format('Y-m-d H:i');
		}
		$connection = $this->getModule()->getConnection();
		$this->request->setAttribute('connection', $connection);
		$this->request->setAttribute('fields', $connection->getRemoteFields());
		return BSView::INPUT;
	}

	public function handleError () {
		return $this->getDefaultView();
	}
}

/* vim:set tabstop=4: */
