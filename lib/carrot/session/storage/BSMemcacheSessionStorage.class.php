<?php
/**
 * @package org.carrot-framework
 * @subpackage session.storage
 */

/**
 * memcacheセッションストレージ
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSMemcacheSessionStorage.class.php 1888 2010-02-28 10:07:43Z pooza $
 */
class BSMemcacheSessionStorage implements BSSessionStorage {

	/**
	 * 初期化
	 *
	 * @access public
	 * @return string 利用可能ならTrue
	 */
	public function initialize () {
		if (!BSMemcacheManager::getInstance()->isEnabled()) {
			return false;
		}
		ini_set('session.save_handler', 'memcache');
		ini_set('session.save_path', BS_MEMCACHE_HOST . ':' . BS_MEMCACHE_PORT);
		return true;
	}
}

/* vim:set tabstop=4: */
