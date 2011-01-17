<?php
/**
 * @package org.carrot-framework
 * @subpackage view.renderer.smarty.plugins
 */

/**
 * GPS対応のリンクを貼り付ける関数
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: function.gps_link.php 2340 2010-09-12 05:39:12Z pooza $
 */
function smarty_function_gps_link ($params, &$smarty) {
	$params = new BSArray($params);
	if (($useragent = $smarty->getUserAgent()) && $useragent->isMobile()) {
		if (BSString::isBlank($params['contents'])) {
			$url = BSURL::getInstance($params, 'carrot');
		} else {
			$url = BSURL::getInstance($params['contents']);
		}
		$url->setUserAgent($useragent);

		$element = $useragent->getCarrier()->getGPSAnchorElement($url, $params['label']);
		return $element->getContents();
	}
}

/* vim:set tabstop=4: */
