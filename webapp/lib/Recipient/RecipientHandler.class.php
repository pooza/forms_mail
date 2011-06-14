<?php
/**
 * @package jp.co.commons.forms.mail
 */

/**
 * 受取人テーブル
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 */
class RecipientHandler extends BSTableHandler {

	/**
	 * @access public
	 * @param string $criteria 抽出条件
	 * @param string $order ソート順
	 */
	public function __construct ($criteria = null, $order = null) {
		if (!$order) {
			$order = new BSTableFieldSet;
			$order->push('update_date DESC');
			$order->push('create_date DESC');
		}
		parent::__construct($criteria, $order);
	}

	/**
	 * レコード追加可能か？
	 *
	 * @access protected
	 * @return boolean レコード追加可能ならTrue
	 */
	protected function isInsertable () {
		return true;
	}

	/**
	 * 全ステータスを返す
	 *
	 * @access public
	 * @param mixed[] $values 値
	 * @static
	 */
	static public function getStatusOptions () {
		return BSTranslateManager::getInstance()->getHash(array(
			'active',
			'inactive',
			'banned',
		));
	}
}

/* vim:set tabstop=4 */