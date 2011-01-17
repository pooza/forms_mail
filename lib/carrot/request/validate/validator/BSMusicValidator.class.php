<?php
/**
 * @package org.carrot-framework
 * @subpackage request.validate.validator
 */

/**
 * 楽曲バリデータ
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSMusicValidator.class.php 2223 2010-07-20 12:53:42Z pooza $
 */
class BSMusicValidator extends BSValidator {

	/**
	 * 初期化
	 *
	 * @access public
	 * @param string[] $params パラメータ配列
	 */
	public function initialize ($params = array()) {
		$this['invalid_error'] = '正しいファイルではありません。';
		return BSValidator::initialize($params);
	}

	/**
	 * 実行
	 *
	 * @access public
	 * @param mixed $value バリデート対象
	 * @return boolean 妥当な値ならばTrue
	 */
	public function execute ($value) {
		try {
			$file = new BSMusicFile($value['tmp_name']);
			if (!$file->isExists() || !$file->validate()) {
				$this->error = $this['invalid_error'];
				if (!BSString::isBlank($error = $file->getError())) {
					$this->error .= '(' . $error . ')';
				}
			}
		} catch (Exception $e) {
			$this->error = $this['invalid_error'];
		}
		return BSString::isBlank($this->error);
	}
}

/* vim:set tabstop=4: */
