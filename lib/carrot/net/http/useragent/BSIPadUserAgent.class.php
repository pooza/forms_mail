<?php
/**
 * @package org.carrot-framework
 * @subpackage net.http.useragent
 */

/**
 * iPadユーザーエージェント
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSIPadUserAgent.class.php 2442 2010-12-07 03:04:46Z pooza $
 */
class BSIPadUserAgent extends BSWebKitUserAgent {

	/**
	 * @access protected
	 * @param string $name ユーザーエージェント名
	 */
	protected function __construct ($name = null) {
		parent::__construct($name);
		$this->attributes['is_tablet'] = true;
	}

	/**
	 * 一致すべきパターンを返す
	 *
	 * @access public
	 * @return string パターン
	 */
	public function getPattern () {
		return 'iPad;';
	}
}

/* vim:set tabstop=4: */
