<?php
/**
 * Deleteアクション
 *
 * @package jp.co.b-shock.forms.mail
 * @subpackage AdminArticle
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 */
class DeleteAction extends BSRecordAction {
	public function execute () {
		try {
			$this->database->beginTransaction();
			$this->getRecord()->delete();
			$this->database->commit();
		} catch (Exception $e) {
			$this->database->rollBack();
			$this->request->setError($this->getTable()->getName(), $e->getMessage());
			return $this->getModule()->getAction('Detail')->forward();
		}
		$url = BSModule::getInstance('AdminConnection')->getAction('Detail')->createURL();
		$url->setParameter('pane', 'ArticleList');
		return $url->redirect();
	}
}

/* vim:set tabstop=4: */
