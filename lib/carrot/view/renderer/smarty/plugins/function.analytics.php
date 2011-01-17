<?php
/**
 * @package org.carrot-framework
 * @subpackage view.renderer.smarty.plugins
 */

/**
 * GoogleAnalytics関数
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: function.analytics.php 2431 2010-11-17 03:45:54Z pooza $
 */
function smarty_function_analytics ($params, &$smarty) {
	$params = new BSArray($params);
	$service = BSGoogleAnalyticsService::getInstance();
	if ($id = $params['id']) {
		$service->setID($id);
	}

	try {
		return $service->getTrackingCode();
	} catch (Exception $e) {
	}
}

/* vim:set tabstop=4: */
