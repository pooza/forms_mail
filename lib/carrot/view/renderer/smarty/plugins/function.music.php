<?php
/**
 * @package org.carrot-framework
 * @subpackage view.renderer.smarty.plugins
 */

/**
 * 楽曲関数
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: function.music.php 2158 2010-06-19 14:18:56Z pooza $
 */
function smarty_function_music ($params, &$smarty) {
	$params = new BSArray($params);
	if (!$file = BSMusicFile::search($params)) {
		return null;
	}

	switch ($mode = BSString::toLower($params['mode'])) {
		case 'seconds':
		case 'duration':
		case 'type':
			return $file[$mode];
		default:
			if (BSString::isBlank($params['href_prefix'])) {
				$finder = new BSRecordFinder($params);
				if ($record = $finder->execute()) {
					$url = BSFileUtility::getURL('musics');
					$url['path'] .= $record->getTable()->getDirectory()->getName() . '/';
					$params['href_prefix'] = $url['path'];
				}
			}
			return $file->getElement($params)->getContents();
	}
}

/* vim:set tabstop=4: */
