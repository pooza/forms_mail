<?php
/**
 * @package org.carrot-framework
 * @subpackage view.renderer.smarty.plugins
 */

/**
 * ケータイ向け出力フィルタ
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: outputfilter.mobile.php 2112 2010-05-29 16:37:08Z pooza $
 */
function smarty_outputfilter_mobile ($source, &$smarty) {
	$source = BSString::convertKana($source, 'kas');
	return $source;
}

/* vim:set tabstop=4: */
