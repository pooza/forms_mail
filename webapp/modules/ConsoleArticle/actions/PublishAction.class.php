<?php
/**
 * Publishアクション
 *
 * @package jp.co.b-shock.forms.mail
 * @subpackage AgentRecipient
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 */
class PublishAction extends BSTableAction {
	protected function getCriteria () {
		if (!$this->criteria) {
			$this->criteria = $this->createCriteriaSet();
			$this->criteria->register('is_published', 0);
			$this->criteria->register('publish_date', BSDate::getNow('Y-m-d H:i:s'), '<=');
		}
		return $this->criteria;
	}

	public function execute () {
		foreach ($this->getTable() as $article) {
			$article->publish();
		}
		return BSView::NONE;
	}
}

/* vim:set tabstop=4: */
