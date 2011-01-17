<?php
/**
 * @package org.carrot-framework
 * @subpackage view.renderer.smarty.plugins
 */

/**
 * 文字コード標準化修飾子
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: modifier.encoding.php 1812 2010-02-03 15:15:09Z pooza $
 */
function smarty_modifier_encoding ($value) {
	if (is_array($value)) {
		return $value;
	} else if ($value instanceof BSParameterHolder) {
		return $value->getParameters();
	} else if (!BSString::isBlank($value)) {
		return BSString::convertEncoding($value, 'utf-8');
	}
}

/* vim:set tabstop=4: */
