<?php
/**
 * @package org.carrot-framework
 * @subpackage request.validate.validator
 */

/**
 * フリガナ項目バリデータ
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSKanaValidator.class.php 2448 2011-01-02 06:16:45Z pooza $
 */
class BSKanaValidator extends BSRegexValidator {
	const PATTERN = '^[ぁ-んァ-ンヴー\\n[:digit:]]*$';

	/**
	 * 初期化
	 *
	 * @access public
	 * @param string[] $params パラメータ配列
	 */
	public function initialize ($params = array()) {
		$this['match'] = true;
		$this['match_error'] = '使用出来ない文字が含まれています。';
		$this['pattern'] = self::PATTERN;
		return BSValidator::initialize($params);
	}
}

/* vim:set tabstop=4: */
