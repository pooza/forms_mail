<?php
/**
 * @package org.carrot-framework
 * @subpackage view.renderer.smarty.plugins
 */

/**
 * ケータイ絵文字関数
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 */
function smarty_function_picto ($params, &$smarty) {
	$pictogram = BSPictogram::getInstance($params['name']);
	return $pictogram->getContents();
}

/* vim:set tabstop=4: */
