<?php
/**
 * @package org.carrot-framework
 * @subpackage media.convertor
 */

/**
 * WebMへの変換
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 */
class BSWebMMediaConvertor extends BSMediaConvertor {

	/**
	 * 変換後のクラス名
	 *
	 * @access public
	 * @return string クラス名
	 */
	public function getClass () {
		return 'BSWebMMovieFile';
	}
}

/* vim:set tabstop=4: */
