<?php
/**
 * @package org.carrot-framework
 * @subpackage net.mime.header.address
 */

/**
 * メールアドレスを格納する抽象ヘッダ
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSAddressMIMEHeader.class.php 1812 2010-02-03 15:15:09Z pooza $
 * @abstract
 */
abstract class BSAddressMIMEHeader extends BSMIMEHeader {
	protected $email;

	/**
	 * 実体を返す
	 *
	 * @access public
	 * @return BSMailAddress 実体
	 */
	public function getEntity () {
		return $this->email;
	}

	/**
	 * 内容を設定
	 *
	 * @access public
	 * @param mixed $contents 内容
	 */
	public function setContents ($contents) {
		if ($contents instanceof BSMailAddress) {
			$contents = $contents->format();
		}
		parent::setContents($contents);
	}

	/**
	 * 内容を追加
	 *
	 * @access public
	 * @param string $contents 内容
	 */
	public function appendContents ($contents) {
		if ($contents instanceof BSMailAddress) {
			$contents = $contents->format();
		}
		parent::appendContents($contents);
	}

	/**
	 * ヘッダの内容からパラメータを抜き出す
	 *
	 * @access protected
	 */
	protected function parse () {
		parent::parse();
		$this->email = BSMailAddress::getInstance($this->contents);
	}
}

/* vim:set tabstop=4: */
