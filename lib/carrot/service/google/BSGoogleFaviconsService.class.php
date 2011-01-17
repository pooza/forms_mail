<?php
/**
 * @package org.carrot-framework
 * @subpackage service.google
 */

/**
 * Google faviconsクライアント
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSGoogleFaviconsService.class.php 2212 2010-07-10 12:53:44Z pooza $
 */
class BSGoogleFaviconsService extends BSCurlHTTP {
	const DEFAULT_HOST = 'www.google.com';

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

	/**
	 * faviconを返す
	 *
	 * @access public
	 * @param BSHTTPRedirector $url 対象URL
	 * @return BSImage PNG画像
	 */
	public function getFavicon (BSHTTPRedirector $url) {
		if ($file = $this->getImageFile($url['host'])) {
			return $file->getRenderer();
		}
	}

	/**
	 * 画像ファイルを返す
	 *
	 * @access public
	 * @param BSHost $host 対象ドメイン
	 * @return BSImageFile 画像ファイル
	 */
	public function getImageFile (BSHost $host) {
		$dir = BSFileUtility::getDirectory('favicon');
		$name = BSCrypt::getDigest($host->getName());
		if (!$file = $dir->getEntry($name, 'BSImageFile')) {
			$query = new BSWWWFormRenderer;
			$query['domain'] = $host->getName();
			try {
				$response = $this->sendGET('/s2/favicons?' . $query->getContents());
				$image = new BSImage;
				$image->setType(BSMIMEType::getType('.png'));
				$image->setImage($response->getRenderer()->getContents());
				$file = BSFileUtility::getTemporaryFile('.png', 'BSImageFile');
				$file->setRenderer($image);
				$file->save();
				$file->setMode(0666);
				$file->rename($name);
				$file->moveTo($dir);
			} catch (Exception $e) {
				return null;
			}
		}
		return $file;
	} 
}

/* vim:set tabstop=4: */
