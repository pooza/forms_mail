<?php
/**
 * アップロード進捗アクション
 *
 * @package org.carrot-framework
 * @subpackage Default
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: UploadProgressAction.class.php 2212 2010-07-10 12:53:44Z pooza $
 */
class UploadProgressAction extends BSAction {
	public function execute () {
		$result = new BSArray(apc_fetch('upload_' . BS_UPLOAD_PROGRESS_KEY));
		if ($result['total'] == $result['current']) {
			$result->clear();
		}
		$renderer = new BSJSONRenderer;
		$renderer->setContents($result);
		$this->request->setAttribute('renderer', $renderer);
		return BSView::SUCCESS;
	}

	public function validate () {
		return extension_loaded('apc');
	}
}

/* vim:set tabstop=4: */
