<?php
/**
 * @package org.carrot-framework
 * @subpackage request.filter
 */

/**
 * 抽象リクエストフィルタ
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSRequestFilter.class.php 1812 2010-02-03 15:15:09Z pooza $
 * @abstract
 */
abstract class BSRequestFilter extends BSFilter {

	/**
	 * 変換して返す
	 *
	 * @access protected
	 * @param mixed $key フィールド名
	 * @param mixed $value 変換対象の文字列又は配列
	 * @return mixed 変換後
	 * @abstract
	 */
	abstract protected function convert ($key, $value);

	public function execute () {
		foreach ($this->request->getParameters() as $key => $value) {
			$this->request[$key] = $this->convert($key, $value);
		}
	}
}

/* vim:set tabstop=4: */
