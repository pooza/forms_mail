<?php
/**
 * @package org.carrot-framework
 */

/**
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSTwitterSearchServiceTest.class.php 2460 2011-01-14 08:01:54Z pooza $
 * @abstract
 */
class BSTwitterSearchServiceTest extends BSTest {
	public function execute () {
		$this->assert('__construct', $service = new BSTwitterSearchService);
		$this->assert('searchTweets_twitter', !!$service->searchTweets('twitter')->count());
		$this->assert('searchTweets_#twitter', !!$service->searchTweets('#twitter')->count());
	}
}

/* vim:set tabstop=4: */
