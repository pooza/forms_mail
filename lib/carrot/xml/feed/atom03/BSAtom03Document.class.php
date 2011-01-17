<?php
/**
 * @package org.carrot-framework
 * @subpackage xml.feed.atom03
 */

/**
 * Atom0.3文書
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSAtom03Document.class.php 2249 2010-08-04 17:15:42Z pooza $
 */
class BSAtom03Document extends BSXMLDocument implements BSFeedDocument {
	protected $version = '0.3';
	protected $namespace = 'http://purl.org/atom/ns#';

	/**
	 * @access public
	 * @param string $name 要素の名前
	 */
	public function __construct ($name = null) {
		parent::__construct('feed');
		$this->setNamespace($this->namespace);
		$this->setAttribute('version', $this->version);
		$this->setDate(BSDate::getNow());
		$this->createElement('generator', BSController::getInstance()->getName());
		$author = BSAuthorRole::getInstance();
		$this->setAuthor($author->getName('ja'), $author->getMailAddress('ja'));
	}

	/**
	 * エントリー要素の名前を返す
	 *
	 * @access public
	 * @return string
	 */
	public function getEntryElementName () {
		return 'entry';
	}

	/**
	 * エントリー要素要素の格納先を返す
	 *
	 * @access public
	 * @return BSXMLElement
	 */
	public function getEntryRootElement () {
		return $this;
	}

	/**
	 * 妥当な文書か？
	 *
	 * @access public
	 * @return boolean 妥当な文書ならTrue
	 */
	public function validate () {
		return (parent::validate()
			&& $this->query('/feed/id')
			&& $this->query('/feed/title')
			&& $this->query('/feed/updated')
			&& $this->query('/feed/author')
			&& $this->query('/feed/link')
		);
	}

	/**
	 * メディアタイプを返す
	 *
	 * @access public
	 * @return string メディアタイプ
	 */
	public function getType () {
		return BSMIMEType::getType('atom');
	}

	/**
	 * タイトルを返す
	 *
	 * @access public
	 * @return string タイトル
	 */
	public function getTitle () {
		if ($element = $this->getElement('title')) {
			return $element->getBody();
		}
	}

	/**
	 * タイトルを設定
	 *
	 * @access public
	 * @param string $title タイトル
	 */
	public function setTitle ($title) {
		if (!$element = $this->getElement('title')) {
			$element = $this->createElement('title');
		}
		$element->setBody($title);
	}

	/**
	 * ディスクリプションを設定
	 *
	 * @access public
	 * @param string $description ディスクリプション
	 */
	public function setDescription ($description) {
		if (!$element = $this->getElement('subtitle')) {
			$element = $this->createElement('subtitle');
		}
		$element->setBody($description);
	}

	/**
	 * リンクを返す
	 *
	 * @access public
	 * @return BSHTTPURL リンク
	 */
	public function getLink () {
		if ($element = $this->getElement('link')) {
			return BSURL::getInstance($element->getBody());
		}
	}

	/**
	 * リンクを設定
	 *
	 * @access public
	 * @param BSHTTPRedirector $link リンク
	 */
	public function setLink (BSHTTPRedirector $link) {
		if (!$element = $this->getElement('id')) {
			$element = $this->createElement('id');
		}
		$element->setBody(BSAtom03Entry::getID($link->getURL()));

		if (!$element = $this->getElement('link')) {
			$element = $this->createElement('link');
		}
		$element->setBody($link->getURL()->getContents());
		$element->setAttribute('href', $link->getURL()->getContents());
	}

	/**
	 * オーサーを設定
	 *
	 * @access public
	 * @param string $name 名前
	 * @param BSMailAddress $email メールアドレス
	 */
	public function setAuthor ($name, BSMailAddress $email = null) {
		if (!$author = $this->getElement('author')) {
			$author = $this->createElement('author');
		}

		if (!$element = $author->getElement('name')) {
			$element = $author->createElement('name');
		}
		$element->setBody($name);

		if ($email) {
			if (!$element = $author->getElement('email')) {
				$element = $author->createElement('email');
			}
			$element->setBody($email->getContents());
		}
	}

	/**
	 * 日付を返す
	 *
	 * @access public
	 * @return BSDate 日付
	 */
	public function getDate () {
		if ($element = $this->getElement('modified')) {
			return BSDate::getInstance($element->getBody());
		}
	}

	/**
	 * 日付を設定
	 *
	 * @access public
	 * @param BSDate $date 日付
	 */
	public function setDate (BSDate $date) {
		if (!$element = $this->getElement('modified')) {
			$element = $this->createElement('modified');
		}
		$element->setBody($date->format(DATE_RFC3339));
	}

	/**
	 * エントリーを生成して返す
	 *
	 * @access public
	 * @return BSFeedEntry エントリー
	 */
	public function createEntry () {
		return BSFeedUtility::createEntry($this);
	}

	/**
	 * Zend形式のフィードオブジェクトを変換
	 *
	 * @access public
	 * @param Zend_Feed_Abstract $feed 変換対象
	 * @return BSFeedDocument
	 */
	public function convert (Zend_Feed_Abstract $feed) {
		$this->setTitle($feed->title());
		foreach ($feed as $entry) {
			try {
				$element = $this->createEntry();
				$element->setTitle($entry->title());

				$link = $entry->link;
				if (is_array($link)) {
					$link = $link[0];
				}
				if (!BSString::isBlank($url = $link->getDOM()->getAttribute('href'))) {
					$element->setLink(BSURL::getInstance($url));
				}

				if ($values = new BSArray($entry->modified())) {
					$element->setDate(BSDate::getInstance($values[0]));
				}
			} catch (Exception $e) {
			}
		}
	}

	/**
	 * エントリーのタイトルを配列で返す
	 *
	 * @access public
	 * @return BSArray
	 */
	public function getEntryTitles () {
		return BSFeedUtility::getEntryTitles($this);
	}
}

/* vim:set tabstop=4: */
