<?php
/**
 * @package org.carrot-framework
 * @subpackage config.parser
 */

BSUtility::includeFile('spyc');

/**
 * YAML設定パーサー
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 */
class BSYAMLConfigParser extends Spyc implements BSConfigParser {
	private $contents;
	private $result;

	/**
	 * 変換前の設定内容を返す
	 *
	 * @access public
	 * @return string 設定内容
	 */
	public function getContents () {
		if (!$this->contents && $this->result) {
			$this->contents = $this->dump($this->result);
		}
		return $this->contents;
	}

	/**
	 * 変換前の設定内容を設定
	 *
	 * @access public
	 * @param string $contents 設定内容
	 */
	public function setContents ($contents) {
		$this->contents = $contents;
		$this->result = null;
	}

	/**
	 * 変換後の設定内容を返す
	 *
	 * @access public
	 * @return mixed[] 設定内容
	 */
	public function getResult () {
		if (!$this->result && !BSString::isBlank($this->contents)) {
			$this->result = BSString::convertKana(parent::YAMLLoad($this->contents), 'KVa');
		}
		return $this->result;
	}

	/**
	 * 結果配列を設定
	 *
	 * @access public
	 * @param mixed $result 結果配列
	 */
	public function setResult ($result) {
		if ($result instanceof BSParameterHolder) {
			$this->result = $result->getParameters();
			$this->contents = null;
		} else {
			$this->result = $result;
			$this->contents = null;
		}
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
	 * メディアタイプを返す
	 *
	 * @access public
	 * @return string メディアタイプ
	 */
	public function getType () {
		return BSMIMEType::getType('yaml');
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
	 * 出力可能か？
	 *
	 * @access public
	 * @return boolean 出力可能ならTrue
	 */
	public function validate () {
		return !!$this->getResult();
	}

	/**
	 * エラーメッセージを返す
	 *
	 * @access public
	 * @return string エラーメッセージ
	 */
	public function getError () {
		return '要素が含まれていません。';
	}
}

/* vim:set tabstop=4: */
