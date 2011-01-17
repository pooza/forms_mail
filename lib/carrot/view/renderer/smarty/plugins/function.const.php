<?php
/**
 * @package org.carrot-framework
 * @subpackage view.renderer.smarty.plugins
 */

/**
 * 定数関数
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: function.const.php 1812 2010-02-03 15:15:09Z pooza $
 */
function smarty_function_const ($params, &$smarty) {
	return BSConstantHandler::getInstance()->getParameter($params['name']);
}

/* vim:set tabstop=4: */
