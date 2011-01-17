<?php
/**
 * QRCodeアクション
 *
 * @package org.carrot-framework
 * @subpackage Default
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: QRCodeAction.class.php 1812 2010-02-03 15:15:09Z pooza $
 */
class QRCodeAction extends BSAction {
	public function execute () {
		$qrcode = new BSQRCode;
		$qrcode->setData($this->request['value']);
		$this->request->setAttribute('renderer', $qrcode);
		return BSView::SUCCESS;
	}
}

/* vim:set tabstop=4: */
