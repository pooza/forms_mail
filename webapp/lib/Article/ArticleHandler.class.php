<?php
/**
 * @package jp.co.commons.forms.mail
 */

/**
 * 記事テーブル
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 */
class ArticleHandler extends BSTableHandler {

	/**
	 * @access public
	 * @param string $criteria 抽出条件
	 * @param string $order ソート順
	 */
	public function __construct ($criteria = null, $order = null) {
		if (!$order) {
			$order = new BSTableFieldSet;
			$order->push('publish_date DESC');
			$order->push('update_date DESC');
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
	 * レコード追加
	 *
	 * @access public
	 * @param mixed $values 値
	 * @param integer $flags フラグのビット列
	 *   BSDatabase::WITH_LOGGING ログを残さない
	 * @return string レコードの主キー
	 */
	public function createRecord ($values, $flags = null) {
		$id = parent::createRecord($values, $flags);
		$this->getRecord($id)->touch();
		return $id;
	}

	/**
	 * 子クラスを返す
	 *
	 * @access public
	 * @return BSArray 子クラス名の配列
	 * @static
	 */
	public function getChildClasses () {
		return new BSArray(array(
			'MailLog',
		));
	}

	/**
	 * 添付ファイル名を全てを返す
	 *
	 * @access public
	 * @return BSArray 添付ファイル名
	 * @static
	 */
	static public function getAttachmentNames () {
		return new BSArray(array(
			'body_template',
			'body_mobile_template',
		));
	}
}

/* vim:set tabstop=4 */