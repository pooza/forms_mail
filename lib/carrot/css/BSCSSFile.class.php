<?php
/**
 * @package org.carrot-framework
 * @subpackage css
 */

/**
 * CSSファイル
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSCSSFile.class.php 2358 2010-09-24 09:12:44Z pooza $
 */
class BSCSSFile extends BSFile {

	/**
	 * バイナリファイルか？
	 *
	 * @access public
	 * @return boolean バイナリファイルならTrue
	 */
	public function isBinary () {
		return false;
	}

	/**
	 * メディアタイプを返す
	 *
	 * @access public
	 * @return string メディアタイプ
	 */
	public function getType () {
		return BSMIMEType::getType('css');
	}

	/**
	 * エンコードを返す
	 *
	 * @access public
	 * @return string PHPのエンコード名
	 */
	public function getEncoding () {
		return 'utf-8';
	}

	/**
	 * シリアライズ
	 *
	 * @access public
	 */
	public function serialize () {
		BSUtility::includeFile('Minify/CSS/Compressor');
		$contents = Minify_CSS_Compressor::process($this->getContents());
		BSController::getInstance()->setAttribute($this, $contents);
	}

	/**
	 * @access public
	 * @return string 基本情報
	 */
	public function __toString () {
		return sprintf('CSSファイル "%s"', $this->getShortPath());
	}
}

/* vim:set tabstop=4: */
