<?php
/**
 * @package org.carrot-framework
 * @subpackage file
 */

/**
 * ディレクトリイテレータ
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSDirectoryIterator.class.php 1812 2010-02-03 15:15:09Z pooza $
 */
class BSDirectoryIterator extends BSIterator {
	private $directory;

	/**
	 * @access public
	 * @param BSDirectory $directory ディレクトリ
	 */
	public function __construct (BSDirectory $directory) {
		$this->directory = $directory;
		parent::__construct($directory->getEntryNames());
	}

	/**
	 * 現在のエントリーを返す
	 *
	 * @access public
	 * @return mixed ファイル又はディレクトリ
	 */
	public function current () {
		return $this->directory->getEntry(parent::current());
	}
}

/* vim:set tabstop=4: */
