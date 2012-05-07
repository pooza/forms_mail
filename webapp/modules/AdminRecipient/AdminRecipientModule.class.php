<?php
/**
 * AdminRecipientモジュール
 *
 * @package jp.co.b-shock.forms.mail
 * @subpackage AdminArtile
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 */
class AdminRecipientModule extends BSModule {

	/**
	 * 接続を返す
	 *
	 * @access public
	 * @return Connection 接続
	 */
	public function getConnection () {
		$module = BSModule::getInstance('AdminConnection');
		if (!$module->getRecord()) {
			if ($recipient = $this->getRecord()) {
				$module->setRecordID($recipient->getConnection());
			}
		}
		return $module->getRecord();
	}

	/**
	 * 抽出条件をシリアライズ
	 *
	 * @access public
	 * @param BSParameterHolder $params パラメータ配列
	 * @return string シリアライズされた抽出条件
	 */
	public function serializeCriteria (BSParameterHolder $params) {
		$serializer = new BSJSONSerializer;
		$params = BSArray::encode($params);
		return $serializer->encode($params->decode());
	}
}

/* vim:set tabstop=4: */
