<?php
/**
 * @package org.carrot-framework
 * @subpackage xml.feed.rss10
 */

/**
 * RSS1.0エントリー
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSRSS10Entry.class.php 1812 2010-02-03 15:15:09Z pooza $
 */
class BSRSS10Entry extends BSRSS09Entry {

	/**
	 * リンクを設定
	 *
	 * @access public
	 * @param BSHTTPRedirector $link リンク
	 */
	public function setLink (BSHTTPRedirector $link) {
		parent::setLink($link);
		if ($seq = $this->document->getItems()->getElement('rdf:Seq')) {
			$li = $seq->createElement('rdf:li');
			$li->setAttribute('rdf:resource', $link->getURL()->getContents());
		}
	}

	/**
	 * 日付を返す
	 *
	 * @access public
	 * @return BSDate 日付
	 */
	public function getDate () {
		if ($element = $this->getElement('dc:date')) {
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
		if (!$element = $this->getElement('dc:date')) {
			$element = $this->createElement('dc:date');
		}
		$element->setBody($date->format(DATE_RFC3339));
	}
}

/* vim:set tabstop=4: */
