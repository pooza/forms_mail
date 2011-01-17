<?php
/**
 * @package org.carrot-framework
 */

/**
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSEnglishValidatorTest.class.php 2460 2011-01-14 08:01:54Z pooza $
 * @abstract
 */
class BSEnglishValidatorTest extends BSTest {
	public function execute () {
		$this->assert('__construct', $validator = new BSEnglishValidator);
		$this->assert('execute', $validator->execute('english'));
		$this->assert('execute', $validator->execute("\n"));
		$this->assert('execute', !$validator->execute('日本語'));
	}
}

/* vim:set tabstop=4: */
