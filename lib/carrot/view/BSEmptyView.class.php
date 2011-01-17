<?php
/**
 * @package org.carrot-framework
 * @subpackage view
 */

/**
 * レンダラーを持たないビュー
 *
 * アクションがBSView::NONEを返したとき、HEADリクエストされたとき等に使用。
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSEmptyView.class.php 1812 2010-02-03 15:15:09Z pooza $
 */
class BSEmptyView extends BSView {

	/**
	 * 初期化
	 *
	 * @access public
	 * @return boolean 初期化が成功すればTrue
	 */
	public function initialize () {
		return true;
	}

	/**
	 * レンダリング
	 *
	 * ヘッダの送信のみ。
	 *
	 * @access public
	 */
	public function render () {
		$this->putHeaders();
	}
}

/* vim:set tabstop=4: */
