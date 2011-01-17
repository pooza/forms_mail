<?php
/**
 * @package org.carrot-framework
 * @subpackage view.renderer.smarty.plugins
 */

/**
 * メールアドレス変換修飾子
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: modifier.email2link.php 1812 2010-02-03 15:15:09Z pooza $
 */
function smarty_modifier_email2link ($value) {
	if (is_array($value)) {
		return $value;
	} else if ($value instanceof BSParameterHolder) {
		return $value->getParameters();
	} else if (!BSString::isBlank($value)) {
		return mb_ereg_replace(
			'[-+._[:alnum:]]+@([-._[:alnum:]]+)+[[:alpha:]]+',
			'<a href="mailto:\\0">\\0</a>',
			$value
		);
	}
}

/* vim:set tabstop=4: */
