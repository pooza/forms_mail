<?php
/**
 * @package org.carrot-framework
 * @subpackage date
 */

/**
 * 祝日リスト
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSHolidayList.interface.php 1812 2010-02-03 15:15:09Z pooza $
 */
interface BSHolidayList extends ArrayAccess {

	/**
	 * 対象日付を設定
	 *
	 * 対象日付の年月のみ参照され、日は捨てられる。
	 *
	 * @access public
	 * @param BSDate $date 対象日付
	 */
	public function setDate (BSDate $date = null);

	/**
	 * 祝日を返す
	 *
	 * @access public
	 * @return BSArray 祝日配列
	 */
	public function getHolidays ();
}

/* vim:set tabstop=4: */
