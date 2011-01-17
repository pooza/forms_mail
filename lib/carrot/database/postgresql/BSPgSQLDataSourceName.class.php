<?php
/**
 * @package org.carrot-framework
 * @subpackage database.postgresql
 */

/**
 * PostgreSQL用データソース名
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSPgSQLDataSourceName.class.php 2260 2010-08-09 17:08:19Z pooza $
 */
class BSPgSQLDataSourceName extends BSDataSourceName {

	/**
	 * @access public
	 * @param mixed[] $params 要素の配列
	 */
	public function __construct ($contents, $name = 'default') {
		parent::__construct($contents, $name);

		mb_ereg('^pgsql:(.+)$', $contents, $matches);
		foreach (mb_split(' +', $matches[1]) as $config) {
			$config = BSString::explode('=', $config);
			switch ($config[0]) {
				case 'host':
					$this['host'] = new BSHost($config[1]);
					break;
				case 'dbname':
					$this['database_name'] = $config[1];
					break;
				case 'user':
					$this['uid'] = $config[1];
					break;
			}
		}
	}

	/**
	 * データベースに接続して返す
	 *
	 * @access public
	 * @return BSDatabase データベース
	 */
	public function getDatabase () {
		return new BSPostgreSQLDatabase($this->getContents());
	}
}

/* vim:set tabstop=4: */
