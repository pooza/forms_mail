<?php
/**
 * @package org.carrot-framework
 * @subpackage view.renderer
 */

/**
 * プレーンテキストレンダラー
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 */
class BSPlainTextRenderer implements BSTextRenderer, IteratorAggregate {
	private $encoding = 'UTF-8';
	private $lineSeparator = "\n";
	private $convertKanaFlag = 'KV';
	private $option = 0;
	private $width = null;
	private $contents;
	const TAIL_LF = 1;

	/**
	 * 出力内容を返す
	 *
	 * @access public
	 */
	public function getContents () {
		$contents = $this->contents;

		if ($this->convertKanaFlag) {
			$contents = BSString::convertKana($contents, $this->convertKanaFlag);
		}
		if ($this->width) {
			$contents = BSString::split($contents, $this->width);
		}
		if ($this->getOption(self::TAIL_LF)) {
			$contents .= "\n\n"; //AppleMail対応
		}
		$contents = BSString::convertLineSeparator($contents, $this->lineSeparator);
		$contents = BSString::convertEncoding($contents, $this->getEncoding());
		return $contents;
	}

	/**
	 * 出力内容を設定
	 *
	 * @param string $contents 出力内容
	 * @access public
	 */
	public function setContents ($contents) {
		$this->contents = $contents;
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
		return BSMIMEType::getType('txt');
	}

	/**
	 * 出力可能か？
	 *
	 * @access public
	 * @return boolean 出力可能ならTrue
	 */
	public function validate () {
		return true;
	}

	/**
	 * エラーメッセージを返す
	 *
	 * @access public
	 * @return string エラーメッセージ
	 */
	public function getError () {
		return null;
	}

	/**
	 * エンコードを返す
	 *
	 * @access public
	 * @return string PHPのエンコード名
	 */
	public function getEncoding () {
		return $this->encoding;
	}

	/**
	 * エンコードを設定
	 *
	 * @access public
	 * @param string $encoding エンコード名
	 */
	public function setEncoding ($encoding) {
		$this->encoding = $encoding;
	}

	/**
	 * 改行コードを設定
	 *
	 * @access public
	 * @param string $separator 改行コード
	 */
	public function setLineSeparator ($separator) {
		$this->lineSeparator = $separator;
	}

	/**
	 * カナ変換フラグを設定
	 *
	 * @access public
	 * @param string $flag フラグ
	 */
	public function setConvertKanaFlag ($flag) {
		$this->convertKanaFlag = $flag;
	}

	/**
	 * オプションが設定されているか？
	 *
	 * @access public
	 * @param integer $option オプション
	 */
	public function getOption ($option) {
		return ($this->option & $option);
	}

	/**
	 * オプションを設定
	 *
	 * @access public
	 * @param integer $option オプションの和
	 *    self::TAIL_LF 末尾に改行を追加
	 */
	public function setOptions ($option) {
		$this->option += $option;
	}

	/**
	 * オプションをクリア
	 *
	 * @access public
	 */
	public function clearOptions () {
		$this->option = 0;
	}

	/**
	 * 行幅を設定
	 *
	 * @access public
	 * @param integer $width 行幅
	 */
	public function setWidth ($width) {
		$this->width = $width;
	}

	/**
	 * @access public
	 * @return BSIterator イテレータ
	 */
	public function getIterator () {
		return new BSIterator(BSString::explode($this->lineSeparator, $this->contents));
	}
}

/* vim:set tabstop=4: */
