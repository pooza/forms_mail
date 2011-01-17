<?php
/**
 * @package org.carrot-framework
 * @subpackage net.mime.header.address
 */

/**
 * Return-Pathヘッダ
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSReturnPathMIMEHeader.class.php 2378 2010-10-08 14:10:29Z pooza $
 */
class BSReturnPathMIMEHeader extends BSAddressMIMEHeader {
	protected $name = 'Return-Path';

	/**
	 * ヘッダの内容からパラメータを抜き出す
	 *
	 * @access protected
	 */
	protected function parse () {
		BSMIMEHeader::parse();
		if (mb_ereg('^<?([^>]*)>?$', $this->contents, $matches)) {
			$this->email = BSMailAddress::getInstance($matches[1]);
		}
	}
}

/* vim:set tabstop=4: */
