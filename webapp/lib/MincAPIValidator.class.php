<?php
/**
 * @package jp.co.commons.forms
 */

/**
 * MINC互換 APIキーバリデータ
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: MincAPIValidator.class.php 6039 2011-02-18 08:16:53Z pooza $
 */
class MincAPIValidator extends BSValidator {

	/**
	 * 実行
	 *
	 * @access public
	 * @param mixed $value バリデート対象
	 * @return boolean 妥当な値ならばTrue
	 */
	public function execute ($value) {
		$url = $this->request->getURL();
		if ($value != self::createAPIKey($url['path'])) {
			$this->error = '正しくありません。';
			return false;
		}
		return true;
	}

	static private function createAPIKey ($path) {
		$values = new BSArray;
		$values[] = $path;
		return BSCrypt::digest($values);
	}
}

/* vim:set tabstop=4: */
