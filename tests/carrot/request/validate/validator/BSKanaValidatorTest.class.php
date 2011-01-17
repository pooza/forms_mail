<?php
/**
 * @package org.carrot-framework
 */

/**
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSKanaValidatorTest.class.php 2460 2011-01-14 08:01:54Z pooza $
 * @abstract
 */
class BSKanaValidatorTest extends BSTest {
	public function execute () {
		$this->assert('__construct', $validator = new BSKanaValidator);
		$this->assert('execute', $validator->execute('アイウエオ'));
		$this->assert('execute', $validator->execute('あいうえお'));
		$this->assert('execute', !$validator->execute('english'));
		$this->assert('execute', $validator->execute("\n"));
	}
}

/* vim:set tabstop=4: */
