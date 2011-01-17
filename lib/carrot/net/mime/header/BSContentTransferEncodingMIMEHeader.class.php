<?php
/**
 * @package org.carrot-framework
 * @subpackage net.mime.header
 */

/**
 * Content-Transfer-Encodingヘッダ
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSContentTransferEncodingMIMEHeader.class.php 2378 2010-10-08 14:10:29Z pooza $
 */
class BSContentTransferEncodingMIMEHeader extends BSMIMEHeader {
	protected $name = 'Content-Transfer-Encoding';

	/**
	 * 内容を設定
	 *
	 * @access public
	 * @param mixed $contents 内容
	 */
	public function setContents ($contents) {
		if ($contents instanceof BSRenderer) {
			$this->contents = self::getContentTransferEncoding($contents);
		} else {
			$this->contents = BSString::toLower($contents);
		}
	}

	/**
	 * レンダラーのContent-Transfer-Encodingを返す
	 *
	 * @access public
	 * @param BSRenderer $renderer レンダラー
	 * @return string Content-Transfer-Encoding
	 * @static
	 */
	static public function getContentTransferEncoding (BSRenderer $renderer) {
		if ($renderer instanceof BSTextRenderer) {
			if (BSString::toLower($renderer->getEncoding()) == 'iso-2022-jp') {
				return '7bit';
			}
			return '8bit';
		}
		return 'base64';
	}
}

/* vim:set tabstop=4: */
