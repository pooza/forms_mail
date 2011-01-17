<?php
/**
 * @package org.carrot-framework
 * @subpackage export
 */

/**
 * エクスポート可能
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSExportable.interface.php 1812 2010-02-03 15:15:09Z pooza $
 */
interface BSExportable {

	/**
	 * エクスポータを返す
	 *
	 * @access public
	 * @return BSExporter エクスポータ
	 */
	public function getExporter ();

	/**
	 * 見出しを返す
	 *
	 * @access public
	 * @return BSArray 見出し
	 */
	public function getHeader ();

	/**
	 * エクスポート
	 *
	 * @access public
	 * @return BSExporter エクスポーター
	 */
	public function export ();
}

/* vim:set tabstop=4: */
