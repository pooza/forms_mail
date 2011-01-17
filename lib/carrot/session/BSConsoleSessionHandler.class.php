<?php
/**
 * @package org.carrot-framework
 * @subpackage session
 */

/**
 * コンソール環境用セッションハンドラ
 *
 * セッション機能が必要な状況がない為、現状は単なるモック。
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSConsoleSessionHandler.class.php 1812 2010-02-03 15:15:09Z pooza $
 */
class BSConsoleSessionHandler extends BSSessionHandler {

	/**
	 * @access public
	 */
	public function __construct () {
	}
}

/* vim:set tabstop=4: */
