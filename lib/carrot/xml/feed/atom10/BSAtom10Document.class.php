<?php
/**
 * @package org.carrot-framework
 * @subpackage xml.feed.atom10
 */

/**
 * Atom1.0文書
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSAtom10Document.class.php 1812 2010-02-03 15:15:09Z pooza $
 */
class BSAtom10Document extends BSAtom03Document {
	protected $version = '1.0';
	protected $namespace = 'http://www.w3.org/2005/Atom';

	/**
	 * 日付を返す
	 *
	 * @access public
	 * @return BSDate 日付
	 */
	public function getDate () {
		if ($element = $this->getElement('updated')) {
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
		if (!$element = $this->getElement('updated')) {
			$element = $this->createElement('updated');
		}
		$element->setBody($date->format(DATE_RFC3339));
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

				if ($values = new BSArray($entry->updated())) {
					$element->setDate(BSDate::getInstance($values[0]));
				}
			} catch (Exception $e) {
			}
		}
	}
}

/* vim:set tabstop=4: */
