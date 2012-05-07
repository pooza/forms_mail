<?php
/**
 * @package org.carrot-framework
 */

/**
 * 配列
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 */
class BSArray extends BSParameterHolder {
	const POSITION_TOP = true;
	const POSITION_BOTTOM = false;
	const SORT_KEY_ASC = 'KEY_ASC';
	const SORT_KEY_DESC = 'KEY_DESC';
	const SORT_VALUE_ASC = 'VALUE_ASC';
	const SORT_VALUE_DESC = 'VALUE_DESC';

	/**
	 * @access public
	 * @param mixed[] $params 要素の配列
	 */
	public function __construct ($params = array()) {
		$this->setParameters($params);
	}

	/**
	 * 別の配列をマージ
	 *
	 * ハッシュではない普通の配列同士は、setParametersではマージできない。
	 *
	 * @access public
	 * @param mixed $values 配列
	 */
	public function merge ($values) {
		if ($values instanceof BSParameterHolder) {
			$values = $values->getParameters();
		} else if (!is_array($values)) {
			return;
		}
		foreach ($values as $value) {
			$this->push($value);
		}
	}

	/**
	 * 要素を設定
	 *
	 * @access public
	 * @param string $name 名前
	 * @param mixed $value 要素
	 * @param boolean $position 先頭ならTrue
	 */
	public function setParameter ($name, $value, $position = self::POSITION_BOTTOM) {
		if (BSString::isBlank($name)) {
			if ($position == self::POSITION_TOP) {
				$this->unshift($value);
			} else {
				$this->push($value);
			}
		} else {
			if ($position == self::POSITION_TOP) {
				$this->params = array((string)$name => null) + $this->params;
			}
			$this->params[(string)$name] = $value;
		}
	}

	/**
	 * 先頭要素を削除し、返す
	 *
	 * @access public
	 * @return mixed 削除された先頭要素
	 */
	public function shift () {
		return array_shift($this->params);
	}

	/**
	 * 先頭に要素を加える
	 *
	 * @access public
	 * @param mixed $value 要素
	 * @return BSArray 自分自身
	 */
	public function unshift ($value) {
		array_unshift($this->params, $value);
		return $this;
	}

	/**
	 * 末尾要素を削除し、返す
	 *
	 * @access public
	 * @return mixed 削除された末尾要素
	 */
	public function pop () {
		return array_pop($this->params);
	}

	/**
	 * 末尾に要素を加える
	 *
	 * @access public
	 * @param mixed $value 要素
	 * @return BSArray 自分自身
	 */
	public function push ($value) {
		$this->params[] = $value;
		return $this;
	}

	/**
	 * ソート
	 *
	 * @access public
	 * @param string $order ソート順
	 * @return BSArray 自分自身
	 */
	public function sort ($order = self::SORT_KEY_ASC) {
		$funcs = new BSArray;
		$funcs[self::SORT_KEY_ASC] = 'ksort';
		$funcs[self::SORT_KEY_DESC] = 'krsort';
		$funcs[self::SORT_VALUE_ASC] = 'asort';
		$funcs[self::SORT_VALUE_DESC] = 'arsort';

		if (BSString::isBlank($func = $funcs[$order])) {
			throw new InvalidArgumentException('BSArray::sortの引数が正しくありません。');
		}
		$func($this->params);
		return $this;
	}

	/**
	 * シャッフル
	 *
	 * @access public
	 */
	public function shuffle () {
		shuffle($this->params);
	}

	/**
	 * 値が含まれているか？
	 *
	 * @access public
	 * @param mixed $values 値、又は値の配列
	 * @return boolean 値が含まれていればTrue
	 */
	public function isContain ($values) {
		foreach (new BSArray($values) as $value) {
			if (in_array($value, $this->getParameters())) {
				return true;
			}
		}
		return false;
	}

	/**
	 * 要素をユニーク化
	 *
	 * @access public
	 * @return BSArray 自分自身
	 */
	public function uniquize () {
		if (version_compare(PHP_VERSION, '5.2.9', '<')) {
			$this->params = array_unique($this->params);
		} else {
			$this->params = array_unique($this->params, SORT_STRING);
		}
		return $this;
	}

	/**
	 * 要素をフラット化
	 *
	 * @access public
	 * @param string $glue 接続子
	 * @return BSArray 自分自身
	 */
	public function flatten ($glue = '_') {
		$this->params = self::getFlatContents(null, $this->params, $glue);
		return $this;
	}
	static private function getFlatContents ($prefix, $arg, $glue) {
		$contents = array();
		if (is_array($arg) || ($arg instanceof BSParameterHolder)) {
			foreach ($arg as $key => $value) {
				if (!BSString::isBlank($prefix)) {
					$key = $prefix . $glue . $key;
				}
				$contents += self::getFlatContents($key, $value, $glue);
			}
		} else {
			$contents[$prefix] = $arg;
		}
		return $contents;
	}

	/**
	 * トリミング
	 *
	 * @access public
	 * @return BSArray 自分自身
	 */
	public function trim () {
		foreach ($this as $key => $value) {
			if (BSString::isBlank($value)) {
				$this->removeParameter($key);
			}
		}
		return $this;
	}

	/**
	 * デリミタで結合した文字列を返す
	 *
	 * @access public
	 * @param string $recordGlue レコードデリミタ
	 * @param string $fieldGlue フィールドデリミタ
	 * @return string 結果文字列
	 */
	public function join ($recordGlue = null, $fieldGlue = null) {
		if (BSString::isBlank($fieldGlue)) {
			return implode($recordGlue, $this->getParameters());
		} else {
			$records = new BSArray;
			foreach ($this as $key => $value) {
				$records[] = $key . $fieldGlue . $value;
			}
			return $records->join($recordGlue);
		}
	}

	/**
	 * 添字の配列を返す
	 *
	 * @access public
	 * @return BSArray 添字の配列
	 */
	public function getKeys () {
		return new BSArray(array_keys($this->getParameters()));
	}

	/**
	 * 添字と値を反転して返す
	 *
	 * @access public
	 * @return BSArray 反転した配列
	 */
	public function createFlipped () {
		return new BSArray(array_flip($this->getParameters()));
	}

	/**
	 * ランダムな要素を返す
	 *
	 * @access public
	 * @return mixed ランダムな要素
	 */
	public function getRandom () {
		$keys = $this->getKeys();
		$key = $keys[BSNumeric::getRandom(0, $this->count() - 1)];
		return $this[$key];
	}

	/**
	 * PHP配列に戻す
	 *
	 * @access public
	 * @return mixed[] PHP配列
	 */
	public function decode () {
		$values = $this->getParameters();
		foreach ($values as $key => $value) {
			if ($value instanceof BSArray) {
				$values[$key] = $value->decode();
			}
		}
		return $values;
	}

	/**
	 * 再帰的にBSArrayに変換する
	 *
	 * @access public
	 * @param mixed $src 対象配列
	 * @return BSArray
	 * @static
	 */
	static public function encode ($src) {
		if (is_array($src) || ($src instanceof BSParameterHolder)) {
			$dest = new BSArray;
			foreach ($src as $key => $value) {
				if (is_array($value)) {
					$value = self::encode($value);
				}
				$dest[$key] = $value;
			}
		} else {
			$dest = $src;
		}
		return $dest;
	}
}

/* vim:set tabstop=4: */
