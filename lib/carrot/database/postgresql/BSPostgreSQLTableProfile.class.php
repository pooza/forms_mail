<?php
/**
 * @package org.carrot-framework
 * @subpackage database.postgresql
 */

/**
 * PostgreSQLテーブルのプロフィール
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 */
class BSPostgreSQLTableProfile extends BSTableProfile {

	/**
	 * テーブルのフィールドリストを配列で返す
	 *
	 * @access public
	 * @return BSArray フィールドのリスト
	 */
	public function getFields () {
		if (!$this->fields) {
			$query = BSSQL::getSelectQueryString(
				'column_name,data_type,character_maximum_length,is_nullable,column_default',
				'information_schema.columns',
				$this->getCriteria(),
				'ordinal_position'
			);
			$this->fields = new BSArray;
			foreach ($this->database->query($query) as $row) {
				$this->fields[$row['column_name']] = $row;
			}
		}
		return $this->fields;
	}

	/**
	 * テーブルの制約リストを配列で返す
	 *
	 * @access public
	 * @return BSArray 制約のリスト
	 */
	public function getConstraints () {
		if (!$this->constraints) {
			$this->constraints = new BSArray;
			$query = BSSQL::getSelectQueryString(
				'constraint_name AS name,constraint_type AS type',
				'information_schema.table_constraints',
				$this->getCriteria(),
				'constraint_type=' . $this->database->quote('PRIMARY KEY') . ',constraint_name'
			);
			foreach ($this->database->query($query) as $row) {
				$criteria = $this->getCriteria();
				$criteria->register('constraint_name', $row['name']);
				$query = BSSQL::getSelectQueryString(
					'column_name',
					'information_schema.key_column_usage',
					$criteria,
					'ordinal_position'
				);
				if ($row['fields'] = $this->database->query($query)->fetchAll()) {
					$this->constraints[$row['name']] = $row;
				}
			}
		}
		return $this->constraints;
	}

	/**
	 * 抽出条件を返す
	 *
	 * @access protected
	 * @return BSCriteriaSet 抽出条件
	 */
	protected function getCriteria () {
		$criteria = $this->database->createCriteriaSet();
		$criteria->register('table_name', $this->getName());
		return $criteria;
	}
}

/* vim:set tabstop=4: */
