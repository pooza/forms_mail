<?php
/**
 * @package org.carrot-framework
 * @subpackage request.validate.validator
 */

/**
 * NGワードバリデータ
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSNGWordValidator.class.php 2381 2010-10-09 08:46:10Z pooza $
 */
class BSNGWordValidator extends BSValidator {

	/**
	 * 初期化
	 *
	 * @access public
	 * @param string[] $params パラメータ配列
	 */
	public function initialize ($params = array()) {
		$this['word_error'] = '不適切な言葉が含まれています。';
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
		$words = new BSArray;
		foreach (array('carrot', 'application') as $name) {
			$config = BSConfigManager::getInstance()->compile('ng_word/' . $name);
			$words->merge($config['words']);
		}
		foreach ($words as $word) {
			if (BSString::isContain($word, $value)) {
				$this->error = $this['word_error'];
				break;
			}
		}
		return BSString::isBlank($this->error);
	}
}

/* vim:set tabstop=4: */
