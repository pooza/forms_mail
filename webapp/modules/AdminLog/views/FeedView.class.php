<?php
/**
 * Feedビュー
 *
 * @package org.carrot-framework
 * @subpackage AdminFeed
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: FeedView.class.php 2250 2010-08-05 03:02:42Z pooza $
 */
class FeedView extends BSView {
	public function initialize () {
		parent::initialize();
		$this->setRenderer(new BSRSS20Document);
		return true;
	}

	public function execute () {
		$this->renderer->setTitle($this->controller->getHost()->getName());
		$this->renderer->setDescription(BS_APP_NAME_JA . 'の管理ログ');
		$this->renderer->setLink($this->getModule('AdminLog'));
		foreach ($this->request->getAttribute('entries') as $log) {
			$entry = $this->renderer->createEntry();
			$entry->setTitle($log['message']);
			$entry->setDate(BSDate::getInstance($log['date']));
			$message = array(
				'date' => $log['date'],
				'remote_host' => $log['remote_host'],
				'priority' => $log['priority'],
			);
			$entry->setBody(BSString::toString($message, ': ', "\n"));
		}
	}
}

/* vim:set tabstop=4: */
