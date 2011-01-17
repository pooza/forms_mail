<?php
/**
 * @package org.carrot-framework
 * @subpackage serialize.storage
 */

/**
 * memcacheシリアライズストレージ
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSMemcacheSerializeStorage.class.php 1812 2010-02-03 15:15:09Z pooza $
 */
class BSMemcacheSerializeStorage implements BSSerializeStorage {
	private $server;

	/**
	 * 初期化
	 *
	 * @access public
	 * @return string 利用可能ならTrue
	 */
	public function initialize () {
		$manager = BSMemcacheManager::getInstance();
		if (!$manager->isEnabled()) {
			return false;
		}
		$this->server = $manager->getServer();
		return true;
	}

	/**
	 * シリアライザーを返す
	 *
	 * @access private
	 * @param BSSerializer シリアライザー
	 */
	private function getSerializer () {
		return BSSerializeHandler::getInstance()->getSerializer();
	}

	/**
	 * 属性を返す
	 *
	 * @access public
	 * @param string $name 属性の名前
	 * @param BSDate $date 比較する日付 - この日付より古い属性値は破棄
	 * @return mixed 属性値
	 */
	public function getAttribute ($name, BSDate $date = null) {
		if ($entry = $this->getEntry($name)) {
			if (!$date || !$entry['update_date']->isPast($date)) {
				return $entry['contents'];
			}
		}
	}

	/**
	 * 属性を設定
	 *
	 * @access public
	 * @param string $name 属性の名前
	 * @param mixed $value 値
	 * @return string シリアライズされた値
	 */
	public function setAttribute ($name, $value) {
		$values = array(
			'update_date' => BSDate::getNow('Y-m-d H:i:s'),
			'contents' => $value,
		);
		$serialized = $this->getSerializer()->encode($values);
		$this->server->set($name, $serialized);
		return $serialized;
	}

	/**
	 * 属性を削除
	 *
	 * @access public
	 * @param string $name 属性の名前
	 */
	public function removeAttribute ($name) {
		return $this->server->delete($name);
	}

	/**
	 * 属性を全て削除
	 *
	 * @access public
	 */
	public function clearAttributes () {
		return $this->server->flush();
	}

	/**
	 * 属性の更新日を返す
	 *
	 * @access public
	 * @param string $name 属性の名前
	 * @return BSDate 更新日
	 */
	public function getUpdateDate ($name) {
		if ($entry = $this->getEntry($name)) {
			return $entry['update_date'];
		}
	}

	/**
	 * エントリーを返す
	 *
	 * エントリーには、属性の値と更新日が含まれる
	 *
	 * @access private
	 * @param string $name 属性の名前
	 * @return BSArray エントリー
	 */
	private function getEntry ($name) {
		if ($values = $this->server->get($name)) {
			$values = $this->getSerializer()->decode($values);
			$entry = new BSArray($values);
			$entry['update_date'] = BSDate::getInstance($entry['update_date']);
			return $entry;
		}
	}
}

/* vim:set tabstop=4: */
