<?php
/**
 * @package org.carrot-framework
 * @subpackage net.http
 */

/**
 * リダイレクト対象
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSHTTPRedirector.interface.php 1812 2010-02-03 15:15:09Z pooza $
 */
interface BSHTTPRedirector {

	/**
	 * リダイレクト対象
	 *
	 * @access public
	 * @return BSURL
	 */
	public function getURL ();

	/**
	 * リダイレクト
	 *
	 * @access public
	 * @return string ビュー名
	 */
	public function redirect ();
}

/* vim:set tabstop=4: */
