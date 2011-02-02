<?php
/**
 * @package jo.co.commons.forms.mail
 */

/**
 * 記事テーブル
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 */
class ArticleHandler extends BSTableHandler {

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
}

/* vim:set tabstop=4 */