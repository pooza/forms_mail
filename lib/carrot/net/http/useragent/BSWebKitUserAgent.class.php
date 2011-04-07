<?php
/**
 * @package org.carrot-framework
 * @subpackage net.http.useragent
 */

/**
 * Webkitユーザーエージェント
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 */
class BSWebKitUserAgent extends BSUserAgent {

	/**
	 * @access protected
	 * @param string $name ユーザーエージェント名
	 */
	protected function __construct ($name = null) {
		parent::__construct($name);
		$this->supports['html5_video'] = true;
		$this->supports['html5_video_webm'] = $this->isChrome();
		$this->supports['html5_video_h264'] = !$this->isChrome();
		$this->supports['html5_audio'] = true;
		$this->supports['html5_audio_mp3'] = true;
		$this->supports['html5_audio_aac'] = true;
	}

	/**
	 * 一致すべきパターンを返す
	 *
	 * @access public
	 * @return string パターン
	 */
	public function getPattern () {
		return 'AppleWebKit';
	}

	/**
	 * Google Chromeか？
	 *
	 * @access public
	 * @return boolean Google ChromeならTrue
	 */
	public function isChrome () {
		return BSString::isContain('Chrome', $this->getName());
	}
}

/* vim:set tabstop=4: */
