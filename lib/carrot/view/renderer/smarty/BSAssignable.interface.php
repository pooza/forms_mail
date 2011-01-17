<?php
/**
 * @package org.carrot-framework
 * @subpackage view.renderer.smarty
 */

/**
 * アサイン可能
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSAssignable.interface.php 1812 2010-02-03 15:15:09Z pooza $
 */
interface BSAssignable {

	/**
	 * アサインすべき値を返す
	 *
	 * @access public
	 * @return mixed アサインすべき値
	 */
	public function getAssignValue ();
}

/* vim:set tabstop=4: */
