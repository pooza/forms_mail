<?php
/**
 * @package org.carrot-framework
 * @subpackage net
 */

/**
 * ネットワークサービスに関するユーティリティ
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSNetworkService.class.php 1812 2010-02-03 15:15:09Z pooza $
 */
class BSNetworkService {
	const TCP = 'tcp';
	const UDP = 'udp';

	/**
	 * 規定のポート番号を返す
	 *
	 * @access public
	 * @param string $service サービスの名前
	 * @param string $protocol プロトコルの名前
	 * @return integer 規定のポート番号
	 * @static
	 */
	static public function getPort ($service, $protocol = self::TCP) {
		return getservbyname($service, $protocol);
	}

	/**
	 * 規定のサービス名を返す
	 *
	 * @access public
	 * @param integer $port ポート番号
	 * @param string $protocol プロトコルの名前
	 * @return string サービス名
	 * @static
	 */
	static public function getService ($port, $protocol = self::TCP) {
		return getservbyport($port, $protocol);
	}
}

/* vim:set tabstop=4: */
