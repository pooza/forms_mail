<?php
/**
 * @package org.carrot-framework
 * @subpackage view.renderer.smarty.plugins
 */

/**
 * CarrotアプリケーションのURLを貼り付ける関数
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: function.carrot_url.php 2342 2010-09-12 05:57:00Z pooza $
 */
function smarty_function_carrot_url ($params, &$smarty) {
	$params = new BSArray($params);

	if (BSString::isBlank($params['contents'])) {
		$url = BSURL::getInstance($params, 'carrot');
	} else {
		$url = BSURL::getInstance($params['contents']);
	}

	if (!BSString::isBlank($name = $params[BSUserAgent::ACCESSOR])) {
		$useragent = BSUserAgent::getInstance($name);
		$url->setParameter(BSUserAgent::ACCESSOR, $name);
	} else {
		$useragent = $smarty->getUserAgent();
	}
	$url->setUserAgent($useragent);
	return $url->getContents();
}

/* vim:set tabstop=4: */
