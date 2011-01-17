<?php
/**
 * @package org.carrot-framework
 * @subpackage net.http
 */

/**
 * HTTP例外
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSHTTPException.class.php 2407 2010-10-29 12:38:04Z pooza $
 */
class BSHTTPException extends BSNetException {
	private $response;

	/**
	 * 例外を含んだレスポンスを返す
	 *
	 * @return BSHTTPResponse レスポンス
	 */
	public function getResponse () {
		return $this->response;
	}

	/**
	 * レスポンスを格納
	 *
	 * @param BSHTTPResponse $response レスポンス
	 */
	public function setResponse (BSHTTPResponse $response) {
		$this->response = $response;
	}
}

/* vim:set tabstop=4: */
