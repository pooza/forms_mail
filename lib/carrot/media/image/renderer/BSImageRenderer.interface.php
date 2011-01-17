<?php
/**
 * @package org.carrot-framework
 * @subpackage media.image.renderer
 */

/**
 * 画像レンダラー
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSImageRenderer.interface.php 1925 2010-03-21 14:07:55Z pooza $
 */
interface BSImageRenderer extends BSRenderer {

	/**
	 * GD画像リソースを返す
	 *
	 * @access public
	 * @return resource GD画像リソース
	 */
	public function getGDHandle ();

	/**
	 * 幅を返す
	 *
	 * @access public
	 * @return integer 幅
	 */
	public function getWidth ();

	/**
	 * 高さを返す
	 *
	 * @access public
	 * @return integer 高さ
	 */
	public function getHeight ();
}

/* vim:set tabstop=4: */
