<?php
/**
 * @package org.carrot-framework
 * @subpackage net.mime.header.addresses
 */

/**
 * BCCヘッダ
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSBccMIMEHeader.class.php 2378 2010-10-08 14:10:29Z pooza $
 */
class BSBccMIMEHeader extends BSAddressesMIMEHeader {
	protected $name = 'Bcc';

	/**
	 * 可視か？
	 *
	 * @access public
	 * @return boolean 可視ならばTrue
	 */
	public function isVisible () {
		return false;
	}
}

/* vim:set tabstop=4: */
