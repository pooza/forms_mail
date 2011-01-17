<?php
/**
 * @package org.carrot-framework
 * @subpackage view.renderer.smarty.plugins
 */

/**
 * エンコード強制変換フィルタ
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: outputfilter.encoding.php 2112 2010-05-29 16:37:08Z pooza $
 */
function smarty_outputfilter_encoding ($source, &$smarty) {
	$source = BSString::convertEncoding($source, $smarty->getEncoding(), 'utf-8');
	return $source;
}

/* vim:set tabstop=4: */
