<?php
/**
 * @package org.carrot-framework
 * @subpackage database.sqlite
 */

/**
 * SQLite用データソース名
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSSQLiteDataSourceName.class.php 2260 2010-08-09 17:08:19Z pooza $
 */
class BSSQLiteDataSourceName extends BSDataSourceName {

	/**
	 * @access public
	 * @param mixed[] $params 要素の配列
	 */
	public function __construct ($contents, $name = 'default') {
		parent::__construct($contents, $name);
		mb_ereg('^sqlite:(.+)$', $contents, $matches);
		$this['file'] = new BSFile($matches[1]);
	}

	/**
	 * データベースに接続して返す
	 *
	 * @access public
	 * @return BSDatabase データベース
	 */
	public function getDatabase () {
		return new BSSQLiteDatabase($this->getContents());
	}
}

/* vim:set tabstop=4: */
