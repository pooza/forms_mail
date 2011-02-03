<?php
/**
 * @package jp.co.commons.forms.mail
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
	 * レコード追加
	 *
	 * @access public
	 * @param mixed $values 値
	 * @param integer $flags フラグのビット列
	 *   BSDatabase::WITH_LOGGING ログを残さない
	 * @return string レコードの主キー
	 */
	public function createRecord ($values, $flags = null) {
		$values = new BSArray($values);
		if (!BSString::isBlank($values['password'])) {
			$values['password'] = BSCrypt::getInstance()->encrypt($values['password']);
		}
		return parent::createRecord($values, $flags);
	}

	/**
	 * 子クラスを返す
	 *
	 * @access public
	 * @return BSArray 子クラス名の配列
	 * @static
	 */
	public function getChildClasses () {
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
	 * @param string uid BASIC認証のUID
	 * @param string password BSCryptで暗号化されたパスワード
	 * @return BSArray フィールドの配列
	 * @static
	 */
	static public function fetchRemoteFields (BSHTTPRedirector $url, $uid, $password) {
		$fields = new BSArray;
		try {
			$url = $url->getURL();
			$service = new BSCurlHTTP($url['host']);
			$service->setAuth($uid, $password);
			$response = $service->sendGET($url->getFullPath());
			$serializer = new BSJSONSerializer;
			$data = $serializer->decode($response->getRenderer()->getContents());
			foreach ($data['fields'] as $field) {
				$field = new BSArray($field);
				$field['choices'] = new BSArray($field['choices']);
				if (!!$field['choices']->count()) {
					$fields[$field['name']] = $field;
				}
			}
		} catch (Exception $e) {
			$fields->clear();
		}
		return $fields;
	}
}

/* vim:set tabstop=4 */