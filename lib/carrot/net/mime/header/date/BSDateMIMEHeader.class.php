<?php
/**
 * @package org.carrot-framework
 * @subpackage net.mime.header.date
 */

/**
 * Dateヘッダ
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSDateMIMEHeader.class.php 2378 2010-10-08 14:10:29Z pooza $
 */
class BSDateMIMEHeader extends BSMIMEHeader {
	protected $name = 'Date';
	private $date;

	/**
	 * 実体を返す
	 *
	 * @access public
	 * @return BSDate 実体
	 */
	public function getEntity () {
		return $this->date;
	}

	/**
	 * 内容を設定
	 *
	 * @access public
	 * @param mixed $contents 内容
	 */
	public function setContents ($contents) {
		if ($contents instanceof BSDate) {
			$contents = $contents->format('r');
		}
		parent::setContents($contents);
	}

	/**
	 * ヘッダの内容からパラメータを抜き出す
	 *
	 * @access protected
	 */
	protected function parse () {
		parent::parse();
		$this->date = BSDate::getInstance($this->contents);
	}
}

/* vim:set tabstop=4: */
