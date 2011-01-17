<?php
/**
 * @package org.carrot-framework
 * @subpackage view.renderer.smarty.plugins
 */

/**
 * URL省略修飾子
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: modifier.truncate_url.php 1812 2010-02-03 15:15:09Z pooza $
 */
function smarty_modifier_truncate_url ($value, $length = 16) {
	if (is_array($value)) {
		return $value;
	} else if ($value instanceof BSParameterHolder) {
		return $value->getParameters();
	} else if (!BSString::isBlank($value)) {
		foreach (BSString::eregMatchAll('https?://[[:graph:]]+', $value) as $matches) {
			$value = str_replace($matches[0], BSString::truncate($matches[0], $length), $value);
		}
		return $value;
	}
}

/* vim:set tabstop=4: */
