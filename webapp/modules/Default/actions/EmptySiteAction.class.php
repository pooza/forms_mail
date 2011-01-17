<?php
/**
 * EmptySiteアクション
 *
 * @package org.carrot-framework
 * @subpackage Default
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: EmptySiteAction.class.php 1980 2010-04-08 09:21:40Z pooza $
 */
class EmptySiteAction extends BSAction {
	public function execute () {
		return BSView::ERROR;
	}
}

/* vim:set tabstop=4: */
