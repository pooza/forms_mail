<?php
/**
 * Listアクション
 *
 * @package jp.co.commons.forms.mail
 * @subpackage AdminArtile
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 */
class ListAction extends BSTableAction {
	protected function getCriteria () {
		if (!$this->criteria) {
			$this->criteria = $this->createCriteriaSet();
			$this->criteria->register('connection_id', $this->getModule()->getConnection());
		}
		return $this->criteria;
	}

	public function execute () {
		$this->request->setAttribute('recipients', $this->getRows());
		return BSView::INPUT;
	}
}

/* vim:set tabstop=4: */
