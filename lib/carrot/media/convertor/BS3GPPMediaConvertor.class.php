<?php
/**
 * @package org.carrot-framework
 * @subpackage media.convertor
 */

/**
 * 3GPPへの変換
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BS3GPPMediaConvertor.class.php 2146 2010-06-15 17:32:54Z pooza $
 */
class BS3GPPMediaConvertor extends BSMediaConvertor {

	/**
	 * 変換後ファイルのサフィックス
	 *
	 * @access public
	 * @return string サフィックス
	 */
	public function getSuffix () {
		return '.3gp';
	}

	/**
	 * 変換後のクラス名
	 *
	 * @access public
	 * @return string クラス名
	 */
	public function getClass () {
		return 'BSMovieFile';
	}
}

/* vim:set tabstop=4: */
