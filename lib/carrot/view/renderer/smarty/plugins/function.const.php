<?php
/**
 * @package org.carrot-framework
 * @subpackage view.renderer.smarty.plugins
 */

/**
 * 定数関数
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 */
function smarty_function_const ($params, &$smarty) {
	$constants = new BSConstantHandler;
	return $constants[$params['name']];
}

/* vim:set tabstop=4: */
