<?php
/**
 * @package org.carrot-framework
 * @subpackage config.compiler
 */

/**
 * オブジェクト登録設定コンパイラ
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 */
class BSObjectRegisterConfigCompiler extends BSConfigCompiler {
	public function execute (BSConfigFile $file) {
		$this->clearBody();
		$this->putLine('return array(');
		foreach ($file->getResult() as $values) {
			$values = new BSArray($values);
			if (BSString::isBlank($values['class'])) {
				throw new BSConfigException($file . 'で、クラス名が指定されていません。');
			}

			$line = new BSStringFormat('  new %s(%s),');
			$line[] = $values['class'];
			$line[] = self::quote((array)$values['params']);
			$this->putLine($line);
		}
		$this->putLine(');');
		return $this->getBody();
	}
}

/* vim:set tabstop=4: */
