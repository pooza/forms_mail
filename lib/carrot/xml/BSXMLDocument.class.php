<?php
/**
 * @package org.carrot-framework
 * @subpackage xml
 */

/**
 * 整形式XML文書
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 */
class BSXMLDocument extends BSXMLElement implements BSTextRenderer {
	private $dirty = false;
	private $error;

	/**
	 * メディアタイプを返す
	 *
	 * @access public
	 * @return string メディアタイプ
	 */
	public function getType () {
		return BSMIMEType::getType('xml');
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
	 * 出力内容のサイズを返す
	 *
	 * @access public
	 * @return integer サイズ
	 */
	public function getSize () {
		return strlen($this->getContents());
	}

	/**
	 * ダーティモードか？
	 *
	 * @access public
	 * @return boolean ダーティモードならTrue
	 */
	public function isDirty () {
		return $this->dirty;
	}

	/**
	 * ダーティモードを設定
	 *
	 * libxml2がエラーを起こすXML文書を無理やり処理する。
	 *
	 * @access public
	 * @param boolean $mode ダーティモード
	 */
	public function setDirty ($mode) {
		$this->dirty = $mode;
		$this->attributes->clear();
		$this->elements->clear();
		$this->setBody();
	}

	/**
	 * 内容をXMLで返す
	 *
	 * @access public
	 * @return string XML文書
	 */
	public function getContents () {
		$contents = '<?xml version="1.0" encoding="utf-8" ?>' . parent::getContents();
		if ($this->isDirty()) {
			return $contents;
		} else {
			$xml = new DOMDocument('1.0', 'utf-8');
			$xml->loadXML($contents);
			$xml->formatOutput = true;
			$xml->normalizeDocument();
			return $xml->saveXML();
		}
	}

	/**
	 * 妥当な要素か？
	 *
	 * @access public
	 * @return boolean 妥当な要素ならTrue
	 */
	public function validate () {
		if (!parent::getContents()) {
			$this->error = '妥当なXML文書ではありません。';
			return false;
		}
		return true;
	}

	/**
	 * エラーメッセージを返す
	 *
	 * @access public
	 * @return string エラーメッセージ
	 */
	public function getError () {
		return $this->error;
	}

	/**
	 * コメントを削除
	 *
	 * @access public
	 * @param string $value 対象文字列
	 * @return string 変換後の文字列
	 * @static
	 */
	static public function stripComment ($value) {
		return mb_ereg_replace('<!--.*?-->', null, $value);
	}
}

/* vim:set tabstop=4: */
