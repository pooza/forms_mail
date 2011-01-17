<?php
/**
 * @package org.carrot-framework
 * @subpackage net.mail
 */

/**
 * メールアドレス
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSMailAddress.class.php 1812 2010-02-03 15:15:09Z pooza $
 */
class BSMailAddress implements BSAssignable {
	private $contents;
	private $name;
	private $account;
	private $domain;
	private $url;
	private $mx = array();
	const PATTERN = '^([-+._[:alnum:]]+)@(([-._[:alnum:]])+[[:alpha:]]+)$';

	/**
	 * @access private
	 * @param string $contents メールアドレス
	 * @param string $name 名前
	 */
	private function __construct ($contents, $name = null) {
		if (BSString::isBlank($name) && mb_ereg('^(.+) *<(.+)>$', $contents, $matches)) {
			$name = $matches[1];
			$contents = $matches[2];
		}
		if (mb_ereg(self::PATTERN, $contents, $matches)) {
			$this->contents = $contents;
			$this->name = $name;
			$this->account = $matches[1];
			$this->domain = $matches[2];
		}
	}

	/**
	 * ファクトリインスタンスを返す
	 *
	 * @access public
	 * @return BSMailAddress インスタンス
	 * @static
	 */
	static public function getInstance ($contents, $name = null) {
		$email = new self($contents, $name);
		if (!BSString::isBlank($email->getContents())) {
			return $email;
		}
	}

	/**
	 * 内容を返す
	 *
	 * @access public
	 * @return string メールアドレス
	 */
	public function getContents () {
		return $this->contents;
	}

	/**
	 * 名前を返す
	 *
	 * @access public
	 * @return string 名前
	 */
	public function getName () {
		return $this->name;
	}

	/**
	 * ドメイン名を返す
	 *
	 * @access public
	 * @return string ドメイン名
	 */
	public function getDomainName () {
		return $this->domain;
	}

	/**
	 * URLを返す
	 *
	 * @access public
	 * @return BSURL
	 */
	public function getURL () {
		if (!$this->url) {
			$this->url = BSURL::getInstance('mailto:' . $this->getContents());
		}
		return $this->url;
	}

	/**
	 * ドメイン名に対応するMXレコードを返す
	 *
	 * @access private
	 * @return BSArray MXレコードの配列
	 */
	private function getMXRecords () {
		if (!$this->mx) {
			getmxrr($this->getDomainName(), $this->mx);
			$this->mx = new BSArray($this->mx);
		}
		return $this->mx;
	}

	/**
	 * 正しいドメインか？
	 *
	 * @access public
	 * @return boolean 正しいドメインならTrue
	 */
	public function isValidDomain () {
		$domains = $this->getMXRecords();
		$domains[] = $this->getDomainName();
		foreach ($domains as $domain) {
			$host = new BSHost($domain);
			if ($host->isExists()) {
				return true;
			}
		}
		return false;
	}

	/**
	 * メールアドレスを書式化
	 *
	 * @access public
	 * @return string 書式化されたメールアドレス
	 */
	public function format () {
		if (BSString::isBlank($this->getName())) {
			return $this->getContents();
		} else {
			return $this->getName() . ' <' . $this->getContents() . '>';
		}
	}

	/**
	 * キャリアを返す
	 *
	 * @access public
	 * @return BSMobileCarrier キャリア
	 */
	public function getCarrier () {
		foreach (BSMobileCarrier::getNames() as $name) {
			$carrier = BSClassLoader::getInstance()->getObject($name, 'MobileCarrier');
			if (BSString::isContain($carrier->getDomainSuffix(), $this->getContents())) {
				return $carrier;
			}
		}
	}

	/**
	 * ケータイ用のアドレスか？
	 *
	 * @access public
	 * @return boolean ケータイ用ならTrue
	 */
	public function isMobile () {
		return !BSString::isBlank($this->getCarrier());
	}

	/**
	 * アサインすべき値を返す
	 *
	 * @access public
	 * @return mixed アサインすべき値
	 */
	public function getAssignValue () {
		return $this->getContents();
	}

	/**
	 * @access public
	 * @return string 基本情報
	 */
	public function __toString () {
		return sprintf('メールアドレス "%s"', $this->getContents());
	}
}

/* vim:set tabstop=4: */
