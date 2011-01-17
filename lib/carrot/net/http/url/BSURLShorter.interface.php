<?php
/**
 * @package org.carrot-framework
 * @subpackage net.http.url
 */

/**
 * URL短縮機能
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSURLShorter.interface.php 1812 2010-02-03 15:15:09Z pooza $
 */
interface BSURLShorter {

	/**
	 * 短縮URLを返す
	 *
	 * @access public
	 * @param BSHTTPRedirector $url 対象URL
	 * @return BSHTTPURL 短縮URL
	 */
	public function getShortURL (BSHTTPRedirector $url);
}

/* vim:set tabstop=4: */
