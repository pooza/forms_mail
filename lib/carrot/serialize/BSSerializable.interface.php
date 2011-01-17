<?php
/**
 * @package org.carrot-framework
 * @subpackage serialize
 */

/**
 * シリアライズ可能なオブジェクトへのインターフェース
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSSerializable.interface.php 1907 2010-03-10 05:19:39Z pooza $
 */
interface BSSerializable {

	/**
	 * 属性名へシリアライズ
	 *
	 * @access public
	 * @return string 属性名
	 */
	public function serializeName ();

	/**
	 * シリアライズ時の値を返す
	 *
	 * @access public
	 * @return mixed シリアライズ時の値
	 */
	public function getSerialized ();

	/**
	 * シリアライズ
	 *
	 * @access public
	 */
	public function serialize ();

	/**
	 * @access public
	 */
	public function __toString ();
}

/* vim:set tabstop=4: */
