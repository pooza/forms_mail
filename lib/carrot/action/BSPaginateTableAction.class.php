<?php
/**
 * @package org.carrot-framework
 * @subpackage action
 */

/**
 * ページあり一覧画面用 アクションひな形
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSPaginateTableAction.class.php 1812 2010-02-03 15:15:09Z pooza $
 * @abstract
 */
abstract class BSPaginateTableAction extends BSTableAction {

	/**
	 * ページ番号を返す
	 *
	 * @access protected
	 * @return integer ページ番号
	 */
	protected function getPage () {
		if (!$this->page) {
			if (!$page = $this->request['page']) {
				$page = 1;
			}
			$this->getTable()->setPage($page);
			$this->page = $this->getTable()->getPage();
		}
		return $this->page;
	}

	/**
	 * ページサイズを返す
	 *
	 * @access public
	 * @return integer ページサイズ
	 */
	protected function getPageSize () {
		if ($this->request->isMobile()) {
			return 10;
		} else {
			return 50;
		}
	}

	/**
	 * テーブルを返す
	 *
	 * @access public
	 * @return BSTableHandler テーブル
	 */
	public function getTable () {
		if (!$this->table) {
			$this->table = clone $this->getModule()->getTable();
			$this->table->setCriteria($this->getCriteria());
			$this->table->setOrder($this->getOrder());
			$this->table->setPageSize($this->getPageSize());
			$this->table->setPage($this->getPage());
		}
		return $this->table;
	}

	public function initialize () {
		parent::initialize();
		$this->request->setAttribute('page', $this->getPage());
		$this->request->setAttribute('lastpage', $this->getTable()->getLastPage());
		return true;
	}
}

/* vim:set tabstop=4: */
