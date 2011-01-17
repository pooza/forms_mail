<?php
/**
 * @package org.carrot-framework
 * @subpackage export
 */

/**
 * エクスポータ
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSExporter.interface.php 1812 2010-02-03 15:15:09Z pooza $
 */
interface BSExporter {

	/**
	 * 一時ファイルを返す
	 *
	 * @access public
	 * @return BSFile 一時ファイル
	 */
	public function getFile ();

	/**
	 * レコードを追加
	 *
	 * @access public
	 * @param BSArray $record レコード
	 */
	public function addRecord (BSArray $record);
}

/* vim:set tabstop=4: */
