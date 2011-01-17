<?php
/**
 * @package org.carrot-framework
 * @subpackage mobile.carrier
 */

/**
 * SoftBank ケータイキャリア
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSSoftBankMobileCarrier.class.php 2385 2010-10-11 07:19:15Z pooza $
 */
class BSSoftBankMobileCarrier extends BSMobileCarrier {

	/**
	 * ドメインサフィックスを返す
	 *
	 * @access public
	 * @return string ドメインサフィックス
	 */
	public function getDomainSuffix () {
		return 'softbank.ne.jp';
	}

	/**
	 * キャリア名の別名を返す
	 *
	 * @access public
	 * @return BSArray 別名の配列
	 */
	public function getAlternativeNames () {
		return new BSArray(array(
			'yahoo',
			'jphone',
			'vodafone',
			'vf',
		));
	}

	/**
	 * GPS情報を取得するリンクを返す
	 *
	 * @access public
	 * @param BSHTTPRedirector $url 対象リンク
	 * @param string $label ラベル
	 * @return BSAnchorElement リンク
	 */
	public function getGPSAnchorElement (BSHTTPRedirector $url, $label) {
		$url = clone $url->getURL();
		$url['query'] = null;

		$element = new BSAnchorElement;
		$element->setURL('location:auto?url=' . $url->getContents());
		$element->setBody($label);
		return $element;
	}

	/**
	 * GPS情報を返す
	 *
	 * @access public
	 * @return BSArray GPS情報
	 */
	public function getGPSInfo () {
		$pos = BSRequest::getInstance()->getParameter('pos');
		if (mb_ereg('[NS]([\\.[:digit:]]+)[EW]([\\.[:digit:]]+)', $pos, $matches)) {
			return new BSArray(array(
				'lat' => BSGeocodeEntryHandler::dms2deg($matches[1]),
				'lng' => BSGeocodeEntryHandler::dms2deg($matches[2]),
			));
		}
	}
}

/* vim:set tabstop=4: */
