<?php
/**
 * @package org.carrot-framework
 * @subpackage view.renderer.smarty.plugins
 */

/**
 * リンクターゲット修飾子
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: modifier.link_target.php 2327 2010-09-06 10:21:17Z pooza $
 */
function smarty_modifier_link_target ($value) {
	if (is_array($value)) {
		return $value;
	} else if ($value instanceof BSParameterHolder) {
		return $value->getParameters();
	} else if (!BSString::isBlank($value)
		&& ($url = BSURL::getInstance($value))
		&& ($url instanceof BSHTTPURL)) {

		if ($url->isForeign()) {
			return '_blank';
		}
		return '_self';
	}
}
/* vim:set tabstop=4: */
