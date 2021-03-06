<?php
/**
 * Listアクション
 *
 * @package jp.co.b-shock.forms.mail
 * @subpackage AdminArtile
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 */
class ListAction extends BSPaginateTableAction {
	protected function getPageSize () {
		return 20;
	}

	protected function getCriteria () {
		if (!$this->criteria) {
			$this->criteria = $this->createCriteriaSet();
			$this->criteria->register('connection_id', $this->getModule()->getConnection());

			if ($key = $this->request['key']) {
				$this->criteria->register('email', '%' . $key . '%', 'LIKE');
			}
			if ($status = $this->request['status']) {
				$this->criteria->register('status', $status);
			}
		}
		return $this->criteria;
	}

	public function execute () {
		$this->request->setAttribute('recipients', $this->getRows());
		return BSView::INPUT;
	}
}

/* vim:set tabstop=4: */
