<?php
/**
 * @package org.carrot-framework
 * @subpackage view.renderer
 */

/**
 * テキストレンダラー
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSTextRenderer.interface.php 1812 2010-02-03 15:15:09Z pooza $
 */
interface BSTextRenderer extends BSRenderer {

	/**
	 * エンコードを返す
	 *
	 * @access public
	 * @return string PHPのエンコード名
	 */
	public function getEncoding ();
}

/* vim:set tabstop=4: */
