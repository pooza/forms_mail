<?php
/**
 * Defaultモジュール
 *
 * 規定モジュールというよりは、モジュールに属さないユーティリティアクションの集まり。
 *
 * @package org.carrot-framework
 * @subpackage Default
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: DefaultModule.class.php 1812 2010-02-03 15:15:09Z pooza $
 */
class BSDefaultModule extends BSModule {

	/**
	 * タイトルを返す
	 *
	 * 固有のモジュールではないと考えられるので、タイトルは不要。
	 *
	 * @access public
	 * @return string タイトル
	 */
	public function getTitle () {
		return null;
	}
}

/* vim:set tabstop=4: */
