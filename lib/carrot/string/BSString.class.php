<?php
/**
 * @package org.carrot-framework
 * @subpackage string
 */

/**
 * 文字列に関するユーティリティ
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 */
class BSString {

	/**
	 * @access private
	 */
	private function __construct () {
	}

	/**
	 * エンコード変換
	 *
	 * @access public
	 * @param mixed $value 変換対象の文字列又は配列
	 * @param string $encodingTo 変換後エンコード
	 * @param mixed $encodingFrom 変換前エンコード、又はその配列
	 * @return mixed 変換後
	 * @static
	 */
	static public function convertEncoding ($value, $encodingTo = null, $encodingFrom = null) {
		if (is_array($value) || ($value instanceof BSParameterHolder)) {
			foreach ($value as $key => $item) {
				$value[$key] = self::convertEncoding($item, $encodingTo, $encodingFrom);
			}
		} else {
			if (self::isBlank($encodingTo)) {
				$encodingTo = 'utf-8';
			}
			if (self::isBlank($encodingFrom)) {
				$encodingFrom = self::getEncodings()->getParameters();
			}
			if ($encodingFrom != $encodingTo) {
				$value = mb_convert_encoding($value, $encodingTo, $encodingFrom);
			}
		}
		return $value;
	}

	/**
	 * エンコードを返す
	 *
	 * @access public
	 * @param string $str 評価対象の文字列
	 * @return string PHPのエンコード名
	 * @static
	 */
	static public function getEncoding ($str) {
		return self::toLower(mb_detect_encoding($str, self::getEncodings()->getParameters()));
	}

	/**
	 * 文字列のサニタイズ
	 *
	 * @access public
	 * @param mixed $value 変換対象の文字列又は配列
	 * @return mixed 変換後
	 * @static
	 */
	static public function sanitize ($value) {
		if (is_array($value) || ($value instanceof BSParameterHolder)) {
			foreach ($value as $key => $item) {
				$value[$key] = self::sanitize($item);
			}
		} else {
			$value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
		}
		return $value;
	}

	/**
	 * サニタイズされた文字列を元に戻す
	 *
	 * @access public
	 * @param mixed $value 変換対象の文字列又は配列
	 * @return mixed 変換後
	 * @static
	 */
	static public function unsanitize ($value) {
		if (is_array($value) || ($value instanceof BSParameterHolder)) {
			foreach ($value as $key => $item) {
				$value[$key] = self::unsanitize($item);
			}
		} else {
			$value = html_entity_decode($value, ENT_QUOTES, 'UTF-8');
		}
		return $value;
	}

	/**
	 * 全角・半角を標準化
	 *
	 * @access public
	 * @param mixed $value 変換対象の文字列又は配列
	 * @param string $format 変換の形式
	 * @return mixed 変換後
	 * @static
	 */
	static public function convertKana ($value, $format = 'KVa') {
		if (is_array($value) || ($value instanceof BSParameterHolder)) {
			foreach ($value as $key => $item) {
				$value[$key] = self::convertKana($item, $format);
			}
		} else {
			if (self::isContain('a-', $format)) {
				$format = str_replace('a-', null, $format);
				$value = self::convertAlphabet($value);
			}
			return mb_convert_kana($value, $format, self::getEncoding($value));
		}
		return $value;
	}

	/**
	 * アルファベットを半角化
	 *
	 * @access public
	 * @param mixed $value 変換対象の文字列又は配列
	 * @return mixed 変換後
	 * @static
	 */
	static public function convertAlphabet ($value) {
		if (is_array($value) || ($value instanceof BSParameterHolder)) {
			foreach ($value as $key => $item) {
				$value[$key] = self::convertAlphabet($item, $format);
			}
		} else {
			foreach (self::eregMatchAll('[［］｛｝（）[:alnum:]]+', $value) as $matches) {
				$value = str_replace($matches[0], mb_convert_kana($matches[0], 'a'), $value);
			}
		}
		return $value;
	}

	/**
	 * 機種依存文字等を置き換え
	 *
	 * @access public
	 * @param mixed $value 変換対象の文字列又は配列
	 * @return mixed 変換後
	 * @static
	 * @link http://php.nekosuke.com/000056.htm 参考
	 */
	static public function convertWrongCharacters ($value) {
		if (is_array($value) || ($value instanceof BSParameterHolder)) {
			foreach ($value as $key => $item) {
				$value[$key] = self::convertWrongCharacters($item);
			}
		} else {
			$searches = array();
			$replaces = array();
			foreach (BSConfigManager::getInstance()->compile('wrong_characters') as $rule) {
				$searches[] = eval('return "' . $rule['search'] . '";');
				$replaces[] = $rule['replace'];
			}

			$value = str_replace($searches, $replaces, $value);
		}
		return $value;
	}

	/**
	 * 機種依存文字等が含まれているか？
	 *
	 * @access public
	 * @param mixed $value 変換対象の文字列
	 * @return boolean 機種依存文字が含まれていたらTrue
	 * @static
	 * @link http://php.nekosuke.com/000056.htm 参考
	 */
	static public function isContainWrongCharacter ($value) {
		foreach (BSConfigManager::getInstance()->compile('wrong_characters') as $rule) {
			$search = eval('return "' . $rule['search'] . '";');
			if (self::isContain($value, $search)) {
				return true;
			}
		}
		return false;
	}

	/**
	 * 改行を標準化
	 *
	 * @access public
	 * @param mixed $value 変換対象の文字列又は配列
	 * @param string $separator 改行文字
	 * @return mixed 変換後
	 * @static
	 */
	static public function convertLineSeparator ($value, $separator = "\n") {
		if (is_array($value) || ($value instanceof BSParameterHolder)) {
			foreach ($value as $key => $item) {
				$value[$key] = self::convertLineSeparator($item, $separator);
			}
		} else {
			$value = str_replace("\r\n", "\n", $value);
			$value = str_replace("\r", "\n", $value);
			$value = str_replace("\n", $separator, $value);
		}
		return $value;
	}

	/**
	 * 文字を規定の長さで切り詰める
	 *
	 * @access public
	 * @param mixed $value 変換対象の文字列又は配列
	 * @param integer $length 長さ
	 * @param string $suffix サフィックス
	 * @return mixed 変換後
	 * @static
	 */
	static public function truncate ($value, $length, $suffix = '...') {
		if (is_array($value) || ($value instanceof BSParameterHolder)) {
			foreach ($value as $key => $item) {
				$value[$key] = self::truncate($item, $length, $suffix);
			}
		} else if ($length < self::getWidth($value)) {
			$value = mb_ereg_replace('[[:space:]]', null, $value);
			$value = self::convertEncoding($value, 'eucjp-win', 'utf-8');
			$value = mb_strcut($value, 0, $length, 'eucjp-win') . $suffix;
			$value = self::convertEncoding($value, 'utf-8', 'eucjp-win');
		}
		return $value;
	}

	/**
	 * キャピタライズされた文字列を返す
	 *
	 * @access public
	 * @param mixed $value 変換対象の文字列又は配列
	 * @return mixed 変換後
	 * @static
	 */
	static public function capitalize ($value) {
		if (is_array($value) || ($value instanceof BSParameterHolder)) {
			foreach ($value as $key => $item) {
				$value[$key] = self::capitalize($item);
			}
		} else {
			$value = ucfirst($value);
		}
		return $value;
	}

	/**
	 * Camel化された文字列を返す
	 *
	 * @access public
	 * @param mixed $value 変換対象の文字列又は配列
	 * @return mixed 変換後
	 * @static
	 */
	static public function camelize ($value) {
		if (is_array($value) || ($value instanceof BSParameterHolder)) {
			foreach ($value as $key => $item) {
				$value[$key] = self::camelize($item);
			}
		} else {
			$value = self::pascalize($value);
			$value[0] = self::toLower($value[0]);
		}
		return $value;
	}

	/**
	 * Palcal化された文字列を返す
	 *
	 * @access public
	 * @param mixed $value 変換対象の文字列又は配列
	 * @return mixed 変換後
	 * @static
	 */
	static public function pascalize ($value) {
		if (is_array($value) || ($value instanceof BSParameterHolder)) {
			foreach ($value as $key => $item) {
				$value[$key] = self::pascalize($item);
			}
		} else {
			$value = str_replace('_', ' ', $value);
			$value = ucwords($value);
			$value = str_replace(' ', '', $value);
		}
		return $value;
	}

	/**
	 * アンダースコア化された文字列を返す
	 *
	 * @access public
	 * @param mixed $value 変換対象の文字列又は配列
	 * @return mixed 変換後
	 * @static
	 */
	static public function underscorize ($value) {
		if (is_array($value) || ($value instanceof BSParameterHolder)) {
			foreach ($value as $key => $item) {
				$value[$key] = self::underscorize($item);
			}
		} else {
			foreach (self::eregMatchAll('[- _]*[[:upper:]]+[^[:upper:]]*', $value) as $matches) {
				$value = str_replace($matches[0], '_' . $matches[0], $value);
			}
			$value = mb_ereg_replace('_{2,}', '_', $value);
			$value = ltrim($value, '_');
			$value = self::toLower($value);
		}
		return $value;
	}

	/**
	 * 名前をインクリメント
	 *
	 * 末尾が数値ならインクリメント。そうでなければ末尾に "2" を加える。
	 *
	 * @access public
	 * @param mixed $value 変換対象の文字列又は配列
	 * @return mixed 変換後
	 * @static
	 */
	static public function increment ($value) {
		if (is_array($value) || ($value instanceof BSParameterHolder)) {
			foreach ($value as $key => $item) {
				$value[$key] = self::underscorize($item);
			}
		} else {
			if (mb_ereg('^(.*)([0-9]+)$', $value, $matches)) {
				$value = $matches[1] . ($matches[2] + 1);
			} else {
				$value .= '2';
			}
		}
		return $value;
	}

	/**
	 * 全て大文字にして返す
	 *
	 * @access public
	 * @param mixed $value 変換対象の文字列又は配列
	 * @return mixed 変換後
	 * @static
	 */
	static public function toUpper ($value) {
		if (is_array($value) || ($value instanceof BSParameterHolder)) {
			foreach ($value as $key => $item) {
				$value[$key] = self::toUpper($item);
			}
		} else {
			$value = mb_strtoupper($value);
		}
		return $value;
	}

	/**
	 * 全て小文字にして返す
	 *
	 * @access public
	 * @param mixed $value 変換対象の文字列又は配列
	 * @return mixed 変換後
	 * @static
	 */
	static public function toLower ($value) {
		if (is_array($value) || ($value instanceof BSParameterHolder)) {
			foreach ($value as $key => $item) {
				$value[$key] = self::toLower($item);
			}
		} else {
			$value = mb_strtolower($value);
		}
		return $value;
	}

	/**
	 * セパレータで分割した配列を返す
	 *
	 * @access public
	 * @param string $separator セパレータ
	 * @param string $str 対象文字列
	 * @return BSArray 結果配列
	 * @static
	 */
	static public function explode ($separator, $str) {
		return new BSArray(explode($separator, $str));
	}

	/**
	 * 半角単位での文字列の幅を返す
	 *
	 * @access public
	 * @param string $str 対象文字列
	 * @return integer 半角単位での幅
	 * @static
	 */
	static public function getWidth ($str) {
		return mb_strwidth($str);
	}

	/**
	 * 指定幅で折り畳む
	 *
	 * @access public
	 * @param string $str 対象文字列
	 * @param integer $witdh 半角単位での行幅
	 * @return string 変換後の文字列
	 * @static
	 */
	static public function split ($str, $width = 74) {
		$str = self::convertEncoding($str, 'eucjp-win', 'utf-8');

		BSUtility::includeFile('OME');
		mb_internal_encoding('eucjp-win');
		$ome = new OME;
		$ome->setBodyWidth($width);
		$str = @$ome->devideWithLimitingWidth($str);
		mb_internal_encoding('utf-8');

		$str = self::convertEncoding($str, 'utf-8', 'eucjp-win');
		return $str;
	}

	/**
	 * 引用文に整形
	 *
	 * @access public
	 * @param string $str 対象文字列
	 * @param integer $witdh 半角単位での行幅
	 * @param string $prefix 行頭記号
	 * @return string 変換後の文字列
	 * @static
	 */
	static public function cite ($str, $width = 74, $prefix = '> ') {
		$str = self::split($str, $width - self::getWidth($prefix));
		$lines = explode("\n", $str);
		foreach ($lines as &$line) {
			$line = $prefix . $line;
		}
		return implode("\n", $lines);
	}

	/**
	 * 空白か？
	 *
	 * @access public
	 * @param string $str 処理対象の文字列
	 * @return boolean 空白ならTrue
	 * @static
	 */
	static public function isBlank ($str) {
		return ($str === '') || ($str === null);
	}

	/**
	 * 指定文字列を含むか？
	 *
	 * @access public
	 * @param string $pattern パターン
	 * @param string $subject 処理対象の文字列
	 * @param boolean $ignore 大文字小文字を無視するか
	 * @return boolean 含むならTrue
	 * @static
	 */
	static public function isContain ($pattern, $subject, $ignore = false) {
		if ($ignore) {
			$function = 'stripos';
		} else {
			$function = 'strpos';
		}
		return ($function($subject, $pattern) !== false);
	}

	/**
	 * HTMLタグとスマートタグを取り除く
	 *
	 * @access public
	 * @param mixed $value 変換対象の文字列又は配列
	 * @return mixed 変換後
	 * @static
	 */
	static public function stripTags ($value) {
		if (is_array($value) || ($value instanceof BSParameterHolder)) {
			foreach ($value as $key => $item) {
				$value[$key] = self::stripTags($item);
			}
		} else {
			$value = self::unsanitize($value);
			$value = strip_tags($value);
			$value = BSSmartTag::parse($value);
		}
		return $value;
	}

	/**
	 * コントロール文字を取り除く
	 *
	 * @access public
	 * @param mixed $value 変換対象の文字列又は配列
	 * @return mixed 変換後
	 * @static
	 */
	static public function stripControlCharacters ($value) {
		if (is_array($value) || ($value instanceof BSParameterHolder)) {
			foreach ($value as $key => $item) {
				$value[$key] = self::stripControlCharacters($item);
			}
		} else {
			$value = mb_ereg_replace('[[:cntrl:]]', '', $value);
		}
		return $value;
	}

	/**
	 * クォートされた文字列から、クォートを外す
	 *
	 * @access public
	 * @param mixed $value 変換対象の文字列又は配列
	 * @return mixed 変換後
	 * @static
	 */
	static public function dequote ($value) {
		if (is_array($value) || ($value instanceof BSParameterHolder)) {
			foreach ($value as $key => $item) {
				$value[$key] = self::dequote($item);
			}
		} else {
			$value = trim($value, '\'"');
		}
		return $value;
	}

	/**
	 * 繰返し正規表現検索を行う
	 *
	 * @access public
	 * @param string $pattern 検索パターン
	 * @param string $subject 入力文字列
	 * @return BSArray マッチした箇所の配列
	 * @static
	 */
	static public function eregMatchAll ($pattern, $subject) {
		$matches = new BSArray;
		if (!BSString::isBlank($pattern) && !BSString::isBlank($subject)) {
			mb_ereg_search_init($subject, $pattern);
			while ($regs = mb_ereg_search_regs()) {
				$matches[] = new BSArray($regs);
			}
		}
		return $matches;
	}

	/**
	 * よく使うエンコード名を返す
	 *
	 * @access public
	 * @return BSArray エンコード名の配列
	 * @static
	 */
	static public function getEncodings () {
		return new BSArray(array(
			'ascii',
			'iso-2022-jp',
			'utf-8',
			'eucjp-win',
			'sjis-win',
		));
	}
}

/* vim:set tabstop=4: */
