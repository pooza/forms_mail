<?php
/**
 * @package org.carrot-framework
 * @subpackage user
 */

/**
 * ユーザー
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 */
class BSUser extends BSParameterHolder {
	protected $id;
	private $attributes;
	private $credentials;
	static private $instance;
	const COOKIE = 1;

	/**
	 * @access protected
	 */
	protected function __construct () {
		$this->attributes = new BSArray;
		$this->attributes->setParameters($_COOKIE);
		$this->attributes->setParameters($this->getSession()->read('attributes'));

		$this->credentials = new BSArray;
		$this->credentials->setParameters($this->getSession()->read('credentials'));

		$this->id = $this->getSession()->read(__CLASS__);
	}

	/**
	 * @access public
	 */
	public function __destruct () {
		$this->getSession()->write('attributes', $this->attributes);
		$this->getSession()->write('credentials', $this->credentials);
	}

	/**
	 * シングルトンインスタンスを返す
	 *
	 * @access public
	 * @return BSUser インスタンス
	 * @static
	 */
	static public function getInstance () {
		if (!self::$instance) {
			if (!BSString::isBlank($class = BS_USER_CLASS)) {
				self::$instance = new $class;
			} else if (BSRequest::getInstance()->isMobile()) {
				self::$instance = new BSMobileUser;
			} else {
				self::$instance = new self;
			}
		}
		return self::$instance;
	}

	/**
	 * @access public
	 */
	public function __clone () {
		throw new BadFunctionCallException(__CLASS__ . 'はコピーできません。');
	}

	/**
	 * 全ての属性を削除
	 *
	 * @access public
	 */
	public function clearAttributes () {
		$this->attributes->clear();
	}

	/**
	 * 属性値を返す
	 *
	 * @access public
	 * @param string $name 属性名
	 * @param integer $flags フラグのビット列
	 *   self::COOKIE cookieのみ
	 * @return mixed 属性値
	 */
	public function getAttribute ($name, $flags = null) {
		if ($flags & self::COOKIE) {
			if (isset($_COOKIE[$name])) {
				return $_COOKIE[$name];
			}
			return null;
		}
		return $this->attributes[$name];
	}

	/**
	 * 属性値が存在するか？
	 *
	 * @access public
	 * @param string $name 属性名
	 * @return boolean 属性値が存在すればTrue
	 */
	public function hasAttribute ($name) {
		return $this->attributes->hasParameter($name);
	}

	/**
	 * 属性値を設定
	 *
	 * @access public
	 * @param string $name 属性名
	 * @param mixed $value 属性値
	 * @param BSDate $expire 期限
	 * @param string $domain 対象ドメイン
	 */
	public function setAttribute ($name, $value, BSDate $expire = null, $domain = null) {
		$this->attributes[(string)$name] = $value;
		if ($expire) {
			if (BSString::isBlank($domain)) {
				$domain = BSController::getInstance()->getHost()->getName();
			}
			setcookie((string)$name, $value, $expire->getTimestamp(), '/', $domain, false, true);
		}
	}

	/**
	 * 属性値を削除
	 *
	 * @access public
	 * @param string $name 属性名
	 * @param string $domain Cookieの対象ドメイン
	 */
	public function removeAttribute ($name, $domain = null) {
		$this->attributes->removeParameter($name);

		if (BSString::isBlank($domain)) {
			$domain = BSController::getInstance()->getHost()->getName();
		}
		$expire = BSDate::getNow();
		$expire['hour'] = '-1';
		setcookie($name, null, $expire->getTimestamp(), '/', $domain);
	}

	/**
	 * 属性値を全て返す
	 *
	 * @access public
	 * @return BSArray 属性値
	 */
	public function getAttributes () {
		return $this->attributes;
	}

	/**
	 * 属性値をまとめて設定
	 *
	 * @access public
	 * @param mixed[] $attributes 属性値
	 */
	public function setAttributes ($attributes) {
		foreach ($attributes as $key => $value) {
			$this->setAttribute($key, $value);
		}
	}

	/**
	 * セッションを返す
	 *
	 * @access protected
	 * @return BSSession セッション
	 */
	protected function getSession () {
		return BSRequest::getInstance()->getSession();
	}

	/**
	 * ユーザーIDを返す
	 *
	 * @access public
	 * @return string ユーザーID
	 */
	public function getID () {
		return $this->id;
	}

	/**
	 * ログイン
	 *
	 * @access public
	 * @param BSUserIdentifier $id ユーザーIDを含んだオブジェクト
	 * @param string $password パスワード
	 * @return boolean 成功ならTrue
	 */
	public function login (BSUserIdentifier $identifier = null, $password = null) {
		if ($identifier && $identifier->auth($password)) {
			$this->id = $identifier->getID();
			$this->getSession()->write(__CLASS__, $this->id);
			$this->getSession()->refresh();
			foreach ($identifier->getCredentials() as $credential) {
				$this->addCredential($credential);
			}
			return true;
		}
		return false;
	}

	/**
	 * ログアウト
	 *
	 * @access public
	 */
	public function logout () {
		$this->id = null;
		$this->clearCredentials();
		$this->getSession()->write(__CLASS__, null);
		$this->getSession()->refresh();
	}

	/**
	 * 全てのクレデンシャルを返す
	 *
	 * @access public
	 * @return BSArray 全てのクレデンシャル
	 */
	public function getCredentials () {
		return $this->credentials;
	}

	/**
	 * クレデンシャルを追加
	 *
	 * @access public
	 * @param string $credential クレデンシャル
	 */
	public function addCredential ($credential) {
		$this->credentials[$credential] = true;
	}

	/**
	 * クレデンシャルを削除
	 *
	 * @access public
	 * @param string $credential クレデンシャル
	 */
	public function removeCredential ($credential) {
		$this->credentials[$credential] = false;
	}

	/**
	 * 全てのクレデンシャルを削除
	 *
	 * @access public
	 */
	public function clearCredentials () {
		$this->credentials->clear();
	}

	/**
	 * クレデンシャルを持っているか？
	 *
	 * @access public
	 * @param string $name クレデンシャル名
	 * @return boolean 持っていればTrue
	 */
	public function hasCredential ($name) {
		return BSString::isBlank($name) || $this->credentials[$name];
	}

	/**
	 * 管理者権限を持っているか？
	 *
	 * @access public
	 * @return boolean 持っていればTrue
	 */
	public function isAdministrator () {
		return $this->hasCredential(BSAdministratorRole::CREDENTIAL);
	}

	/**
	 * 発行者権限を持っているか？
	 *
	 * @access public
	 * @return boolean 持っていればTrue
	 */
	public function isAuthor () {
		return $this->hasCredential(BSAuthorRole::CREDENTIAL);
	}

	/**
	 * ゲストユーザーか？
	 *
	 * @access public
	 * @return boolean ゲストユーザーならばTrue
	 */
	public function isGuest () {
		foreach ($this->getCredentials() as $credential) {
			if (!!$credential) {
				return false;
			}
		}
		return true;
	}
}

/* vim:set tabstop=4: */
