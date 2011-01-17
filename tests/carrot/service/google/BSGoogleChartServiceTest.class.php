<?php
/**
 * @package org.carrot-framework
 */

/**
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSGoogleChartServiceTest.class.php 2463 2011-01-15 06:01:29Z pooza $
 * @abstract
 */
class BSGoogleChartServiceTest extends BSTest {
	public function execute () {
		$this->assert('__construct', $service = new BSGoogleChartService);

		$url = BSURL::getInstance('http://www.b-shock.co.jp/');
		$this->assert(
			'getQRCodeImageFile',
			($service->getQRCodeImageFile($url->getContents()) instanceof BSImageFile)
		);
	}
}

/* vim:set tabstop=4: */
