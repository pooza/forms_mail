<?php
/**
 * @package org.carrot-framework
 * @subpackage filter
 */

/**
 * 抽象フィルタ
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @abstract
 */
abstract class BSFilter extends BSParameterHolder {
	static protected $executed;

	/**
	 * @access public
	 * @param string[] $params パラメータ配列
	 */
	public function __construct ($params = array()) {
		if (!self::$executed) {
			self::$executed = new BSArray;
		}
		$this->initialize($params);
	}

	/**
	 * @access public
	 * @param string $name プロパティ名
	 * @return mixed 各種オブジェクト
	 */
	public function __get ($name) {
		switch ($name) {
			case 'controller':
			case 'request':
			case 'user':
				return BSUtility::executeMethod($name, 'getInstance');
			case 'action':
				return $this->controller->getAction();
		}
	}

	/**
	 * 初期化
	 *
	 * @access public
	 * @param mixed[] $params パラメータ
	 * @return boolean 初期化が成功すればTrue
	 */
	public function initialize ($params = array()) {
		$this->setParameters($params);
		return true;
	}

	/**
	 * フィルタ名を返す
	 *
	 * @access public
	 * @return string フィルタ名
	 */
	public function getName () {
		return get_class($this);
	}

	/**
	 * 実行
	 *
	 * @access public
	 * @return boolean 終了ならばTrue
	 */
	abstract public function execute ();

	/**
	 * 実行できるか
	 *
	 * @access public
	 * @return boolean 実行できるならTrue
	 */
	public function isExecutable () {
		return (!$this->isExecuted() || $this->isRepeatable()) && !$this->isExcludedAction();
	}

	/**
	 * 二度目も実行するか
	 *
	 * @access public
	 * @return boolean 二度目も実行するならTrue
	 */
	public function isRepeatable () {
		return false;
	}

	/**
	 * 実行済みフラグを設定
	 *
	 * @access public
	 * @param boolean $flag 実行されたならTrue
	 */
	public function setExecuted ($flag = true) {
		self::$executed[$this->getName()] = $flag;
	}

	/**
	 * 実行されたか？
	 *
	 * @access public
	 * @return boolean 実行されたならTrue
	 */
	public function isExecuted () {
		return !!self::$executed[$this->getName()];
	}

	/**
	 * 除外されたアクションか？
	 *
	 * @access public
	 * @return boolean 除外されたアクションならTrue
	 */
	public function isExcludedAction () {
		$actions = new BSArray($this['excluded_actions']);
		return $actions->isContain($this->action->getName());
	}

	/**
	 * @access public
	 * @return string 基本情報
	 */
	public function __toString () {
		return sprintf('フィルタ "%s"', get_class($this));
	}
}

/* vim:set tabstop=4: */
