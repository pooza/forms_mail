<?php
/**
 * Loginビュー
 *
 * @package org.carrot-framework
 * @subpackage Default
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: LoginView.class.php 2429 2010-11-16 11:17:25Z pooza $
 */
class LoginView extends BSSmartyView {

	/**
	 * HTTPキャッシュ有効か
	 *
	 * @access public
	 * @return boolean 有効ならTrue
	 */
	public function isCacheable () {
		return false;
	}
}

/* vim:set tabstop=4: */
