<?php
/**
 * @package org.carrot-framework
 */

/**
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSGoogleURLShortnerServiceTest.class.php 2463 2011-01-15 06:01:29Z pooza $
 * @abstract
 */
class BSGoogleURLShortnerServiceTest extends BSTest {
	public function execute () {
		$this->assert('__construct', $service = new BSGoogleURLShortnerService);

		$url = BSURL::getInstance('http://www.b-shock.co.jp/');
		$this->assert('getShortURL', ($service->getShortURL($url) instanceof BSHTTPURL));
	}
}

/* vim:set tabstop=4: */
