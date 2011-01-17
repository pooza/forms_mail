<?php
/**
 * @package org.carrot-framework
 * @subpackage filter
 */

/**
 * ホスト認証
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSHostSecurityFilter.class.php 1936 2010-03-25 13:50:22Z pooza $
 */
class BSHostSecurityFilter extends BSFilter {
	public function execute () {
		try {
			$this->auth();
		} catch (BSNetException $e) {
			$this->controller->getAction('secure')->forward();
			return true;
		}
	}

	/**
	 * クライアントホストによる認証
	 *
	 * @access private
	 * @return 許可されたネットワーク内ならTrue
	 */
	private function auth () {
		foreach (BSAdministratorRole::getInstance()->getAllowedNetworks() as $network) {
			if ($network->isContain($this->request->getHost())) {
				return true;
			}
		}
		throw new BSNetException('リモートアクセス禁止のホストです。');
	}
}

/* vim:set tabstop=4: */
