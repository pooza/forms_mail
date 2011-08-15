<?php
/**
 * @package org.carrot-framework
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 */
class BSAndroidUserAgentTest extends BSTest {
	public function execute () {
		// Xperia SO-01B
		$useragent = BSUserAgent::create(
			'Mozilla/5.0 (Linux; U; Android 1.6; ja-jp; SonyEricssonSO-01B Build/R1EA018) AppleWebKit/528.5+ (KHTML, like Gecko) Version/3.1.2 Mobile Safari/525.20.1'
		);
		$this->assert('getInstance_Xperia', $useragent instanceof BSAndroidUserAgent);
		$this->assert('isSmartPhone_Xperia', $useragent->isSmartPhone());
		$this->assert('isLegacy_Xperia', !$useragent->isLegacy());
		$this->assert('hasSupport_flash_Xperia', !$useragent->hasSupport('flash'));

		// Galaxy Tab
		$useragent = BSUserAgent::create(
			'Mozilla/5.0 (Linux; U; Android 2.2; ja-jp; SC-01C Build/FROYO) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1'
		);
		$this->assert('getInstance_GalaxyTab', $useragent instanceof BSAndroidUserAgent);
		$this->assert('isTablet_GalaxyTab', $useragent->isTablet());
		$this->assert('hasSupport_flash_GalaxyTab', $useragent->hasSupport('flash'));

		// Optimus Pad
		$useragent = BSUserAgent::create(
			'Mozilla/5.0 (Linux; U; Android 3.0.1; ja-jp; L-06C Build/HRI66) AppleWebKit/534.13 (KHTML, like Gecko) Version/4.0 Safari/534.13'
		);
		$this->assert('getInstance_OptimusPad', $useragent instanceof BSAndroidUserAgent);
		$this->assert('isTablet_OptimusPad', $useragent->isTablet());
		$this->assert('hasSupport_flash_OptimusPad', $useragent->hasSupport('flash'));
	}
}

/* vim:set tabstop=4: */
