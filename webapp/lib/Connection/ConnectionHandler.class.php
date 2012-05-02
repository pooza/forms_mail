<?php
/**
 * @package jp.co.b-shock.forms.mail
 */

/**
 * 接続テーブル
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 */
class ConnectionHandler extends BSSortableTableHandler {

	/**
	 * レコード追加可能か？
	 *
	 * @access protected
	 * @return boolean レコード追加可能ならTrue
	 */
	protected function isInsertable () {
		return true;
	}

	/**
	 * 子クラスを返す
	 *
	 * @access public
	 * @return BSArray 子クラス名の配列
	 * @static
	 */
	static public function getChildClasses () {
		return new BSArray(array(
			'Recipient',
			'Article',
		));
	}

	/**
	 * 接続情報から、フィールドの配列を返す
	 *
	 * @access public
	 * @param BSHTTPRedirector $url 対象URL
	 * @return BSArray フィールドの配列
	 * @static
	 */
	static public function fetchRemoteFields (BSHTTPRedirector $url) {
		$fields = new BSArray;
		$url = $url->createURL();
		$url->setParameter('api_key', BSCrypt::digest($url['path']));
		$service = new BSCurlHTTP($url['host']);
		$response = $service->sendGET($url->getFullPath());
		$serializer = new BSJSONSerializer;
		$data = $serializer->decode($response->getRenderer()->getContents());
		foreach ($data['fields'] as $field) {
			$field = new BSArray($field);
			$field['choices'] = new BSArray($field['choices']);
			$fields[$field['name']] = $field;
		}
		return $fields;
	}
}

/* vim:set tabstop=4 */