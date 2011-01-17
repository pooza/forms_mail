<?php
/**
 * @package org.carrot-framework
 * @subpackage config.parser
 */

/**
 * 設定パーサー
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSConfigParser.interface.php 1812 2010-02-03 15:15:09Z pooza $
 */
interface BSConfigParser extends BSTextRenderer {

	/**
	 * 変換前の設定内容を設定
	 *
	 * @access public
	 * @param string $contents 設定内容
	 */
	public function setContents ($contents);

	/**
	 * 変換後の設定内容を返す
	 *
	 * @access public
	 * @return mixed[] 設定内容
	 */
	public function getResult ();
}

/* vim:set tabstop=4: */
