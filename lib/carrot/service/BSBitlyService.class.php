<?php
/**
 * @package org.carrot-framework
 * @subpackage service
 */

/**
 * bit.lyクライアント
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSBitlyService.class.php 2063 2010-05-04 10:45:09Z pooza $
 */
class BSBitlyService extends BSCurlHTTP implements BSURLShorter {
	const DEFAULT_HOST = 'api.bit.ly';

	/**
	 * @access public
	 * @param BSHost $host ホスト
	 * @param integer $port ポート
	 */
	public function __construct (BSHost $host = null, $port = null) {
		if (!$host) {
			$host = new BSHost(self::DEFAULT_HOST);
		}
		parent::__construct($host, $port);
	}

	private function createAPIURL ($command) {
		$url = BSURL::getInstance();
		$url['host'] = $this->getHost();
		$url['path'] = $command;
		$url->setParameter('version', BS_SERVICE_BITLY_VERSION);
		$url->setParameter('login', BS_SERVICE_BITLY_LOGIN);
		$url->setParameter('apiKey', BS_SERVICE_BITLY_API_KEY);
		return $url;
	}

	/**
	 * 短縮URLを返す
	 *
	 * @access public
	 * @param BSHTTPRedirector $url 対象URL
	 * @return BSHTTPURL 短縮URL
	 */
	public function getShortURL (BSHTTPRedirector $url) {
		$request = $this->createAPIURL('shorten');
		$request->setParameter('longUrl', $url->getContents());
		$response = $this->sendGET($request->getFullPath());

		$json = new BSJSONSerializer;
		$result = $json->decode($response->getRenderer()->getContents());
		$result = new BSArray($result['results']);
		$result = new BSArray($result->getIterator()->getFirst());
		return BSURL::getInstance($result['shortUrl']);
	}
}

/* vim:set tabstop=4: */
