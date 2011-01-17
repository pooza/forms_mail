<?php
/**
 * @package org.carrot-framework
 * @subpackage database.table
 */

/**
 * テーブルイテレータ
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSTableIterator.class.php 1812 2010-02-03 15:15:09Z pooza $
 */
class BSTableIterator extends BSIterator {
	private $table;

	/**
	 * @access public
	 * @param BSTableHandler $table テーブル
	 */
	public function __construct (BSTableHandler $table) {
		$this->table = $table;
		foreach ($table->getContents() as $row) {
			$this->keys[] = $row[$table->getKeyField()];
		}
	}

	/**
	 * 現在のレコードを返す
	 *
	 * @access public
	 * @return BSRecord レコード
	 */
	public function current () {
		return $this->table->getRecord(parent::key());
	}

	/**
	 * カーソルを終端に進める
	 *
	 * @access public
	 * @return mixed 最後のエントリー
	 */
	public function forward () {
		$this->cursor = $this->table->count() - 1;
		return $this->current();
	}
}

/* vim:set tabstop=4: */
