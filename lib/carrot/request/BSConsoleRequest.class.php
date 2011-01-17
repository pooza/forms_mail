<?php
/**
 * @package org.carrot-framework
 * @subpackage request
 */

/**
 * コンソールリクエスト
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSConsoleRequest.class.php 2041 2010-04-27 01:05:49Z pooza $
 */
class BSConsoleRequest extends BSRequest {
	private $options;

	/**
	 * @access protected
	 */
	protected function __construct () {
		$this->options = new BSArray;
		$this->addOption(self::MODULE_ACCESSOR);
		$this->addOption(self::ACTION_ACCESSOR);
		$this->parse();

		if (BSString::isBlank($this[self::MODULE_ACCESSOR])) {
			$this[self::MODULE_ACCESSOR] = 'Console';
		}
	}

	/**
	 * コマンドラインパーサオプションを追加
	 *
	 * @access public
	 * @param string $name オプション名
	 */
	public function addOption ($name) {
		$this->options[$name] = array(
			'name' => $name,
		);
	}

	/**
	 * コマンドラインをパース
	 *
	 * @access public
	 */
	public function parse () {
		$config = new BSArray;
		foreach ($this->options as $option) {
			$config[] = $option['name'] . ':';
		}

		$this->clear();
		$this->setParameters(getopt($config->join('')));
	}

	/**
	 * コマンドライン環境か？
	 *
	 * @access public
	 * @return boolean コマンドライン環境ならTrue
	 */
	public function isCLI () {
		return true;
	}

	/**
	 * 出力内容を返す
	 *
	 * @access public
	 */
	public function getContents () {
		return null;
	}

	/**
	 * ヘッダ一式を返す
	 *
	 * @access public
	 * @return string[] ヘッダ一式
	 */
	public function getHeaders () {
		return null;
	}

	/**
	 * レンダラーを返す
	 *
	 * @access public
	 * @return BSRenderer レンダラー
	 */
	public function getRenderer () {
		return null;
	}
}

/* vim:set tabstop=4: */
