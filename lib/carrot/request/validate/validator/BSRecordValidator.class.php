<?php
/**
 * @package org.carrot-framework
 * @subpackage request.validate.validator
 */

/**
 * レコードバリデータ
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 */
class BSRecordValidator extends BSValidator {
	private $table;

	/**
	 * 初期化
	 *
	 * @access public
	 * @param string[] $params パラメータ配列
	 */
	public function initialize ($params = array()) {
		$this['table'] = null;
		$this['class'] = null;
		$this['field'] = 'id';
		$this['exist'] = true;
		$this['update'] = false;
		$this['exist_error'] = '登録されていません。';
		$this['duplicate_error'] = '重複します。';
		$this['valid_values'] = array();
		$this['criteria'] = array();
		return parent::initialize($params);
	}

	/**
	 * 実行
	 *
	 * @access public
	 * @param mixed $value バリデート対象（レコードのID、又はその配列）
	 * @return boolean 妥当な値ならばTrue
	 */
	public function execute ($value) {
		$ids = new BSArray($value);
		$ids->trim();
		foreach ($ids as $id) {
			if ($this->isExists($id)) {
				if (!$this['exist']) {
					$this->error = $this['duplicate_error'];
					return false;
				} else if ($this['valid_values'] && !$this->validateValues($id)) {
					return false;
				}
			} else {
				if ($this['exist']) {
					$this->error = $this['exist_error'];
					return false;
				}
			}
		}
		return true;
	}

	/**
	 * 該当するレコードが存在するか
	 *
	 * @access private
	 * @param integer $id 対象ID
	 * @return boolean 該当するレコードが存在するならTrue
	 */
	private function isExists ($id) {
		if ($recordFound = $this->getRecord($id)) {
			if ($this['update']) {
				if ($recordModule = $this->controller->getModule()->getRecord()) {
					return ($recordModule->getID() != $recordFound->getID());
				} else {
					return false;
				}
			} else {
				return true;
			}
		}
		return false;
	}

	/**
	 * 該当するレコードのフィールド値が適切か
	 *
	 * @access private
	 * @param integer $id 対象ID
	 * @return boolean 該当するレコードのフィールド値が適切ならTrue
	 */
	private function validateValues ($id) {
		$record = $this->getRecord($id);
		foreach ($this['valid_values'] as $field => $value) {
			$values = new BSArray($value);
			if (!$values->isContain($record[$field])) {
				$message = sprintf(
					'%sが正しくありません。',
					BSTranslateManager::getInstance()->execute($field)
				);
				$this->error = $message;
				return false;
			}
		}
		return true;
	}

	/**
	 * モジュールの関数を実行し、結果を返す
	 *
	 * @access private
	 * @param string $function 関数名
	 * @return mixed 関数の戻り値。BSRecordならIDを、それ以外ならそのまま返す。
	 */
	private function executeModuleFunction ($function) {
		$value = $this->controller->getModule()->$function();
		if ($value instanceof BSRecord) {
			$value = $value->getID();
		}
		return $value;
	}

	/**
	 * 該当するレコードを返す
	 *
	 * @access private
	 * @param integer $id 対象ID
	 * @return BSRecord 該当するレコード
	 */
	private function getRecord ($id) {
		try {
			$values = array($this['field'] => $id);
			foreach ((array)$this['criteria'] as $field => $value) {
				if (isset($value['function'])) {
					$value = $this->executeModuleFunction($value['function']);
				}
				$values[$field] = $value;
			}
			return $this->getTable()->getRecord($values);
		} catch (Exception $e) {
		}
	}

	/**
	 * 対象テーブルを返す
	 *
	 * @access private
	 * @return BSTableHandler 対象テーブル
	 */
	private function getTable () {
		if (!$this->table) {
			if (BSString::isBlank($class = $this['class'])) {
				$class = $this['table'];
			}
			$this->table = BSTableHandler::create($class);
		}
		return $this->table;
	}
}

/* vim:set tabstop=4: */
