<?php
/**
 * @package org.carrot-framework
 * @subpackage filter
 */

/**
 * HTTPSによるGETを強制するフィルタ
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSHTTPSFilter.class.php 1812 2010-02-03 15:15:09Z pooza $
 */
class BSHTTPSFilter extends BSFilter {
	public function initialize ($params = array()) {
		$this['base_url'] = BS_ROOT_URL_HTTPS;
		return parent::initialize($params);
	}

	public function execute () {
		if (!BS_DEBUG
			&& !$this->request->isCLI()
			&& !$this->request->isSSL()
			&& ($this->request->getMethod() == 'GET')) {

			$url = BSURL::getInstance($this['base_url']);
			$url['path'] = $this->controller->getAttribute('REQUEST_URI');
			$url->redirect();
			return true;
		}
	}
}

/* vim:set tabstop=4: */
