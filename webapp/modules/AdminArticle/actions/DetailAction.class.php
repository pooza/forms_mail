<?php
/**
 * Detailアクション
 *
 * @package jp.co.commons.forms.mail
 * @subpackage AdminArticle
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
		$fields = new BSArray($this->request['fields']);
		return array(
			'title' => $this->request['title'],
			'body' => $this->request['body'],
			'criteria' => $this->getModule()->serializeCriteria($fields),
			'publish_date' => $this->request['publish_date'],
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
			$record = $this->getRecord();
			$serializer = new BSJSONSerializer;
			$this->request['fields'] = $serializer->decode($record['criteria']);
			$this->request['selected_fields'] = new BSArray;
			foreach ($this->request['fields'] as $key => $values) {
				$this->request['selected_fields'][$key] = 1;
			}
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