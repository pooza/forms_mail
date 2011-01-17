<?php
/**
 * @package org.carrot-framework
 * @subpackage request.validate.validator
 */

/**
 * JabberIDバリデータ
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSJabberIDValidator.class.php 2448 2011-01-02 06:16:45Z pooza $
 */
class BSJabberIDValidator extends BSRegexValidator {
	const PATTERN = '^([-_.[:alnum:]]+)@(([-_.[:alnum:]]+)+[[:alpha:]]+)(/([-_[:alnum:]]+))?$';

	/**
	 * 初期化
	 *
	 * @access public
	 * @param string[] $params パラメータ配列
	 */
	public function initialize ($params = array()) {
		$this['match'] = true;
		$this['match_error'] = '正しいJabberIDではありません。';
		$this['pattern'] = self::PATTERN;
		return BSValidator::initialize($params);
	}
}

/* vim:set tabstop=4: */
