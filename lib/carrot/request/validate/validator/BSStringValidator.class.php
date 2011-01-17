<?php
/**
 * @package org.carrot-framework
 * @subpackage request.validate.validator
 */

/**
 * 文字列バリデータ
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSStringValidator.class.php 2438 2010-11-29 07:26:18Z pooza $
 */
class BSStringValidator extends BSValidator {
	const MAX_SIZE = 1024;

	/**
	 * 初期化
	 *
	 * @access public
	 * @param string[] $params パラメータ配列
	 */
	public function initialize ($params = array()) {
		$this['max'] = self::MAX_SIZE;
		$this['max_error'] = '長すぎます。';
		$this['min'] = null;
		$this['min_error'] = '短すぎます。';
		$this['invalid_error'] = '正しくありません。';
		$this['pictogram'] = true;
		$this['pictogram_error'] = '絵文字が含まれています。';
		$this['wrong_character'] = true;
		$this['wrong_character_error'] = '機種依存文字が含まれています。';
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
		if (BSArray::isArray($value)) {
			foreach ($value as $entry) {
				$this->execute($entry);
			}
		} else {
			if (!mb_check_encoding($value)) {
				$this->error = $this['invalid_error'];
			}
			if (!BSString::isBlank($this['min']) && (BSString::getWidth($value) < $this['min'])) {
				$this->error = $this['min_error'];
			}
			if (!BSString::isBlank($this['max']) && ($this['max'] < BSString::getWidth($value))) {
				$this->error = $this['max_error'];
			}
			if (!!$this['wrong_character'] && BSString::isContainWrongCharacter($value)) {
				$this->error = $this['wrong_character_error'];
			}
			if (!!$this['pictogram']) {
				if (($useragent = $this->request->getUserAgent()) && $useragent->isMobile()) {
					if ($useragent->getCarrier()->isContainPictogram($value)) {
						$this->error = $this['pictogram_error'];
					}
				}
			}
		}
		return BSString::isBlank($this->error);
	}
}

/* vim:set tabstop=4: */
