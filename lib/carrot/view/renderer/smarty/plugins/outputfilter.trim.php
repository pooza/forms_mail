<?php
/**
 * @package org.carrot-framework
 * @subpackage view.renderer.smarty.plugins
 */

/**
 * トリミング出力フィルタ
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: outputfilter.trim.php 2112 2010-05-29 16:37:08Z pooza $
 */
function smarty_outputfilter_trim ($source, &$smarty) {
	return trim($source);
}

/* vim:set tabstop=4: */
