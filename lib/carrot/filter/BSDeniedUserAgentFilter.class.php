<?php
/**
 * @package org.carrot-framework
 * @subpackage filter
 */

/**
 * 禁止されたUserAgent
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSDeniedUserAgentFilter.class.php 1812 2010-02-03 15:15:09Z pooza $
 */
class BSDeniedUserAgentFilter extends BSFilter {
	public function initialize ($params = array()) {
		$this['module'] = 'Default';
		$this['action'] = 'DeniedUserAgent';
		return parent::initialize($params);
	}

	public function execute () {
		if ($this->request->getUserAgent()->isDenied()) {
			try {
				$module = $this->controller->getModule($this['module']);
				$action = $module->getAction($this['action']);
			} catch (BSException $e) {
				$action = $this->controller->getAction('not_found');
			}
			$this->controller->registerAction($action);
		}
	}
}

/* vim:set tabstop=4: */
