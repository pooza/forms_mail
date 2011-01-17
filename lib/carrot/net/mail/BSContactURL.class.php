<?php
/**
 * @package org.carrot-framework
 * @subpackage net.mail
 */

/**
 * 連絡先URL
 *
 * mailtoとかtelとか。
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSContactURL.class.php 2327 2010-09-06 10:21:17Z pooza $
 */
class BSContactURL extends BSURL {
	private $query;

	/**
	 * @access protected
	 * @param mixed $contents URL
	 */
	protected function __construct ($contents = null) {
		$this->query = new BSWWWFormRenderer;
		parent::__construct($contents);
	}

	/**
	 * 内容を返す
	 *
	 * @access public
	 * @return string URL
	 */
	public function getContents () {
		if (!$this->contents) {
			$this->contents = $this['scheme'] . ':' . $this['path'];
			if ($this->query->count()) {
				$this->contents .= '?' . $this->query->getContents();
			}
		}
		return $this->contents;
	}

	/**
	 * 属性を設定
	 *
	 * @access public
	 * @param string $name 属性の名前
	 * @param mixed $value 値
	 * @return BSURL 自分自身
	 */
	public function setAttribute ($name, $value) {
		switch ($name) {
			case 'scheme':
			case 'path':
				$this->attributes[$name] = $value;
				break;
			case 'query':
				$this->query->setContents($value);
				break;
		}
		$this->contents = null;
		return $this;
	}
}

/* vim:set tabstop=4: */
