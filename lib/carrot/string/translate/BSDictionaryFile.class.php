<?php
/**
 * @package org.carrot-framework
 * @subpackage string.translate
 */

/**
 * 辞書ファイル
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSDictionaryFile.class.php 1843 2010-02-08 12:42:17Z pooza $
 */
class BSDictionaryFile extends BSCSVFile implements BSDictionary {
	private $words;

	/**
	 * @access public
	 * @param string $path パス
	 */
	public function __construct ($path) {
		parent::__construct($path, new BSHeaderCSVData);
		$this->getEngine()->setEncoding('utf-8');
		$this->getEngine()->setRecordSeparator("\n");
	}

	/**
	 * 辞書の内容を返す
	 *
	 * @access public
	 * @return BSArray 辞書の内容
	 */
	public function getWords () {
		if (!$this->words) {
			if (BSString::isBlank($this->getSerialized())) {
				$this->serialize();
			}
			$this->words = new BSArray($this->getSerialized());
		}
		return $this->words;
	}

	/**
	 * 翻訳して返す
	 *
	 * @access public
	 * @param string $label ラベル
	 * @param string $language 言語
	 * @return string 翻訳された文字列
	 */
	public function translate ($label, $language) {
		return $this->getWords()->getParameter($label . '_' . $language);
	}

	/**
	 * 辞書の名前を返す
	 *
	 * @access public
	 * @return string 辞書の名前
	 */
	public function getDictionaryName () {
		return get_class($this) . '.' . $this->getBaseName();
	}

	/**
	 * シリアライズ
	 *
	 * @access public
	 */
	public function serialize () {
		$words = clone $this->getEngine()->getRecords();
		$words->flatten();
		BSController::getInstance()->setAttribute($this, $words);
	}

	/**
	 * @access public
	 * @return string 基本情報
	 */
	public function __toString () {
		return sprintf('辞書ファイル "%s"', $this->getShortPath());
	}
}

/* vim:set tabstop=4: */
