<?php
/**
 * @package org.carrot-framework
 * @subpackage view.renderer.smarty.plugins
 */

/**
 * HTMLタグ削除修飾子
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: modifier.strip_html_tags.php 1812 2010-02-03 15:15:09Z pooza $
 */
function smarty_modifier_strip_html_tags ($value) {
	if (is_array($value)) {
		return $value;
	} else if ($value instanceof BSParameterHolder) {
		return $value->getParameters();
	} else if (!BSString::isBlank($value)) {
		return BSString::stripTags($value);
	}
}

/* vim:set tabstop=4: */
