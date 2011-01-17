<?php
/**
 * Feedアクション
 *
 * @package org.carrot-framework
 * @subpackage AdminLog
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: FeedAction.class.php 1812 2010-02-03 15:15:09Z pooza $
 */
class FeedAction extends BSAction {
	public function execute () {
		$this->request->setAttribute('entries', $this->getModule()->getEntries());
		return BSView::SUCCESS;
	}
}

/* vim:set tabstop=4: */
