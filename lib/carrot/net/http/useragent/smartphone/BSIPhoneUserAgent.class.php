<?php
/**
 * @package org.carrot-framework
 * @subpackage net.http.useragent.smartphone
 */

/**
 * iPhoneユーザーエージェント
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSIPhoneUserAgent.class.php 2351 2010-09-20 07:14:46Z pooza $
 */
class BSIPhoneUserAgent extends BSWebKitUserAgent {

	/**
	 * スマートフォンか？
	 *
	 * @access public
	 * @return boolean スマートフォンならTrue
	 */
	public function isSmartPhone () {
		return true;
	}

	/**
	 * 画面情報を返す
	 *
	 * @access public
	 * @return BSArray 画面情報
	 */
	public function getDisplayInfo () {
		return new BSArray(array(
			'width' => 480,
		));
	}

	/**
	 * 一致すべきパターンを返す
	 *
	 * @access public
	 * @return string パターン
	 */
	public function getPattern () {
		return 'i(Phone|Pod);';
	}
}

/* vim:set tabstop=4: */
