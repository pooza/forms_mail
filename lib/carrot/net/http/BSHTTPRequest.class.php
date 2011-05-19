<?php
/**
 * @package org.carrot-framework
 * @subpackage net.http
 */

/**
 * httpリクエスト
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 */
class BSHTTPRequest extends BSMIMEDocument {
	protected $method;
	protected $version = '1.0';
	protected $url;
	protected $useragent;

	/**
	 * httpバージョンを返す
	 *
	 * @access public
	 * @return string httpバージョン
	 */
	public function getVersion () {
		return $this->version;
	}

	/**
	 * メソッドを返す
	 *
	 * @access public
	 * @return string メソッド
	 */
	public function getMethod () {
		return $this->method;
	}

	/**
	 * メソッドを設定
	 *
	 * @access public
	 * @param string $method メソッド
	 */
	public function setMethod ($method) {
		$this->method = BSString::toUpper($method);
		if (!self::isValidMethod($this->method)) {
			throw new BSHTTPException($this->method . 'は正しくないメソッドです。');
		}
	}

	/**
	 * 送信先URLを返す
	 *
	 * @access public
	 * @return BSURL 送信先URL
	 */
	public function getURL () {
		return $this->url;
	}

	/**
	 * 送信先URLを設定
	 *
	 * @access public
	 * @param BSHTTPRedirector $url 送信先URL
	 */
	public function setURL (BSHTTPRedirector $url) {
		$this->url = $url->getURL();
		$this->setHeader('Host', $this->url['host']);
	}

	/**
	 * 出力内容を返す
	 *
	 * @access public
	 * @return string 出力内容
	 */
	public function getContents () {
		$this->setHeader('Content-Length', $this->getRenderer()->getSize());
		return $this->getRequestLine() . self::LINE_SEPARATOR . parent::getContents();
	}

	/**
	 * リクエスト行を返す
	 *
	 * @access public
	 * @return string 出力内容
	 */
	public function getRequestLine () {
		$line = new BSStringFormat('%s %s HTTP/%s');
		$line[] = $this->getMethod();
		$line[] = $this->getURL()->getFullPath();
		$line[] = $this->getVersion();
		return $line->getContents();
	}

	/**
	 * UserAgentを返す
	 *
	 * @access public
	 * @return BSUserAgent リモートホストのUserAgent
	 */
	public function getUserAgent () {
		if (!$this->useragent) {
			if ($header = $this->getHeader('user-agent')) {
				$this->setUserAgent($header->getEntity());
			}
		}
		return $this->useragent;
	}

	/**
	 * UserAgentを設定
	 *
	 * @access public
	 * @param BSUserAgent $useragent リモートホストのUserAgent
	 */
	public function setUserAgent (BSUserAgent $useragent) {
		$this->useragent = $useragent;
	}

	/**
	 * 出力可能か？
	 *
	 * @access public
	 * @return boolean 出力可能ならTrue
	 */
	public function validate () {
		return $this->getMethod() && $this->getURL();
	}

	/**
	 * エラーメッセージを返す
	 *
	 * @access public
	 * @return string エラーメッセージ
	 */
	public function getError () {
		return 'メソッド又は送信先URLが空欄です。';
	}

	/**
	 * サポートしているメソッドを返す
	 *
	 * @access public
	 * @return BSArray サポートしているメソッド
	 * @static
	 */
	static public function getMethods () {
		$methods = new BSArray;
		$methods[] = 'HEAD';
		$methods[] = 'GET';
		$methods[] = 'POST';
		$methods[] = 'PUT';
		$methods[] = 'DELETE';
		return $methods;
	}

	/**
	 * サポートされたメソッドか？
	 *
	 * @access public
	 * @param string $method メソッド名
	 * @return boolean サポートしているならTrue
	 * @static
	 */
	static public function isValidMethod ($method) {
		return self::getMethods()->isContain(BSString::toUpper($method));
	}
}

/* vim:set tabstop=4: */
