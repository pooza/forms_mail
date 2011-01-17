<?php
/**
 * @package org.carrot-framework
 * @subpackage request.validate.validator
 */

/**
 * 必須バリデータ
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSEmptyValidator.class.php 1812 2010-02-03 15:15:09Z pooza $
 */
class BSEmptyValidator extends BSValidator {

	/**
	 * 初期化
	 *
	 * @access public
	 * @param string[] $params パラメータ配列
	 */
	public function initialize ($params = array()) {
		$this['required_msg'] = '空欄です。';
		return parent::initialize($params);
	}

	/**
	 * 実行
	 *
	 * @access public
	 * @param mixed $value バリデート対象
	 * @return boolean 妥当な値ならばTrue
	 */
	public function execute ($value) {
		if (self::isEmpty($value)) {
			$this->error = $this['required_msg'];
			return false;
		}
		return true;
	}

	/**
	 * フィールド値は空欄か？
	 *
	 * @access public
	 * @return boolean フィールド値が空欄ならばTrue
	 * @static
	 */
	static public function isEmpty ($value) {
		if (BSArray::isArray($value)) {
			$value = new BSArray($value);
			if ($value['is_file']) {
				return BSString::isBlank($value['name']);
			} else {
				$value->trim();
				return !$value->count();
			}
		} else {
			return BSString::isBlank($value);
		}
	}
}

/* vim:set tabstop=4: */
