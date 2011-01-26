<?php
/**
 * @package org.carrot-framework
 * @subpackage xml.xhtml.object
 */

/**
 * YouTube用object要素
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSYouTubeObjectElement.class.php 2473 2011-01-26 03:51:48Z pooza $
 */
class BSYouTubeObjectElement extends BSObjectElement {

	/**
	 * @access public
	 * @param string $name 要素の名前
	 * @param BSUserAgent $useragent 対象UserAgent
	 */
	public function __construct ($name = null, BSUserAgent $useragent = null) {
		parent::__construct($name, $useragent);
		$this->setAttribute('type', BSMIMEType::getType('swf'));
		$this->setParameter('wmode', 'transparent');
		$this->setParameter('allowFullScreen', 'true');
	}

	/**
	 * ムービーを設定
	 *
	 * @access public
	 * @param integer $id ムービーID
	 * @param BSParameterHolder $params パラメータ配列
	 */
	public function setMovie ($id, BSParameterHolder $params = null) {
		$params = new BSArray($params);
		$params->removeParameter('width');
		$params->removeParameter('height');

		$url = BSURL::getInstance();
		$url['host'] = BSYouTubeService::DEFAULT_HOST;
		$url['path'] = '/v/' . $id;
		$url->setParameters($params);

		$this->setAttribute('data', $url->getContents());
	}
}

/* vim:set tabstop=4: */
