<?php
/**
 * @package org.carrot-framework
 * @subpackage media
 */

/**
 * メディアファイル
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @abstract
 */
abstract class BSMediaFile extends BSFile implements ArrayAccess {
	protected $attributes;
	protected $output;
	protected $types;

	/**
	 * @access public
	 * @param string $path パス
	 */
	public function __construct ($path) {
		$this->setPath($path);
		$this->attributes = new BSArray;
		$this->analyze();
	}

	/**
	 * 属性を返す
	 *
	 * @access public
	 * @param string $name 属性名
	 * @return mixed 属性
	 */
	public function getAttribute ($name) {
		return $this->attributes[$name];
	}

	/**
	 * 全ての属性を返す
	 *
	 * @access public
	 * @return BSArray 全ての属性
	 */
	public function getAttributes () {
		return $this->attributes;
	}

	/**
	 * バイナリファイルか？
	 *
	 * @access public
	 * @return boolean バイナリファイルならTrue
	 */
	public function isBinary () {
		return true;
	}

	/**
	 * ファイルを解析
	 *
	 * @access protected
	 */
	protected function analyze () {
		$command = $this->createCommand();
		$command->push('-i', null);
		$command->push($this->getPath());
		$this->output = $command->getResult()->join("\n");

		if (mb_ereg('Error .*$', $this->output, $matches)) {
			$this->attributes['type'] = BSMIMEType::DEFAULT_TYPE;
			$this->error = $matches[0];
			return;
		}

		if (mb_ereg('Duration: ([.:[:digit:]]+),', $this->output, $matches)) {
			$this->attributes['duration'] = $matches[1];
			$sec = BSString::explode(':', $matches[1]);
			$this->attributes['seconds'] = ($sec[0] * 3600) + ($sec[1] * 60) + $sec[2];
		}
		$this->attributes['type'] = $this->analyzeType();
	}

	/**
	 * メディアタイプを返す
	 *
	 * @access public
	 * @return string メディアタイプ
	 */
	public function getType () {
		if (BSString::isBlank($this->attributes['type'])) {
			$this->analyze();
		}
		return $this->attributes['type'];
	}

	/**
	 * 表示用のXHTML要素を返す
	 *
	 * @access public
	 * @param BSParameterHolder $params パラメータ配列
	 * @param BSUserAgent $useragent 対象ブラウザ
	 * @return BSDivisionElement 要素
	 */
	public function createElement (BSParameterHolder $params, BSUserAgent $useragent = null) {
		$params = new BSArray($params);
		$this->resizeByWidth($params, $useragent);

		$container = new BSDivisionElement;
		$container->registerStyleClass($params['style_class']);
		$container->setStyles($this->getStyles($params));
		if ($element = $this->createObjectElement($params)) {
			$container->addElement($element);
		}
		return $container;
	}

	/**
	 * 幅でリサイズ
	 *
	 * @access protected
	 * @param BSParameterHolder $params パラメータ配列
	 * @param BSUserAgent $useragent 対象ブラウザ
	 */
	protected function resizeByWidth (BSParameterHolder $params, BSUserAgent $useragent = null) {
		if ($params['_resized_by_width']) {
			return;
		}

		if (!$useragent) {
			$useragent = BSRequest::getInstance()->getUserAgent();
		}

		$info = $useragent->getDisplayInfo();
		if (!$params['max_width'] && $info['width']) {
			$params['max_width'] = $info['width'] - 20;
		}

		if ($params['max_width'] && ($params['max_width'] < $params['width'])) {
			$params['height'] = BSNumeric::round(
				$params['height'] * $params['max_width'] / $params['width']
			);
			$params['width'] = $params['max_width'];
		}
		$params['_resized_by_width'] = true;
	}

	/**
	 * スタイル属性を返す
	 *
	 * @access protected
	 * @param BSParameterHolder $params パラメータ配列
	 * @return BSCSSSelector スタイル属性
	 */
	protected function getStyles (BSParameterHolder $params) {
		$style = new BSCSSSelector;
		if ($params['width']) {
			$style['width'] = $params['width'] . 'px';
		} else {
			$style['width'] = $this['width'] . 'px';
		}
		if ($params['height']) {
			$style['height'] = $params['height'] . 'px';
		} else {
			$style['height'] = $this['height_full'] . 'px';
		}
		return $style;
	}

	/**
	 * メディアURLを返す
	 *
	 * @access protected
	 * @param BSParameterHolder $params パラメータ配列
	 * @return BSURL メディアURL
	 */
	protected function createURL (BSParameterHolder $params) {
		$url = BSURL::create($params['href_prefix']);
		$url['path'] .= $this->getName() . $params['href_suffix'];
		if (BSUser::getInstance()->isAdministrator()) {
			$url->setParameter('at', BSNumeric::getRandom(1000, 9999));
		}
		return $url;
	}

	/**
	 * div要素のIDを生成して返す
	 *
	 * @access protected
	 * @return string div要素のID
	 */
	protected function createContainerID () {
		return BSCrypt::digest(array(
			get_class($this),
			$this->getID(),
			BSUtility::getUniqueID(),
		));
	}

	/**
	 * script要素を返す
	 *
	 * @access public
	 * @param BSParameterHolder $params パラメータ配列
	 * @return BSScriptElement 要素
	 */
	public function createScriptElement (BSParameterHolder $params) {
	}

	/**
	 * object要素を返す
	 *
	 * @access public
	 * @param BSParameterHolder $params パラメータ配列
	 * @return BSObjectElement 要素
	 */
	public function createObjectElement (BSParameterHolder $params) {
	}

	/**
	 * 出力可能か？
	 *
	 * @access public
	 * @return boolean 出力可能ならTrue
	 */
	public function validate () {
		if (!$this->attributes->count()) {
			$this->analyze();
		}
		return $this->isReadable() && $this->attributes->count();
	}

	/**
	 * @access public
	 * @param string $key 添え字
	 * @return boolean 要素が存在すればTrue
	 */
	public function offsetExists ($key) {
		return $this->attributes->hasParameter($key);
	}

	/**
	 * @access public
	 * @param string $key 添え字
	 * @return mixed 要素
	 */
	public function offsetGet ($key) {
		return $this->getAttribute($key);
	}

	/**
	 * @access public
	 * @param string $key 添え字
	 * @param mixed 要素
	 */
	public function offsetSet ($key, $value) {
		throw new BSMediaException($this . 'の属性を設定できません。');
	}

	/**
	 * @access public
	 * @param string $key 添え字
	 */
	public function offsetUnset ($key) {
		throw new BSMediaException($this . 'の属性を削除できません。');
	}

	/**
	 * コマンドラインを返す
	 *
	 * @access public
	 * @return BSCommandLine コマンドライン
	 */
	public function createCommand () {
		$command = new BSCommandLine('bin/ffmpeg');
		$command->setDirectory(BSFileUtility::getDirectory('ffmpeg'));
		$command->setStderrRedirectable();
		return $command;
	}

	/**
	 * 探す
	 *
	 * @access public
	 * @param mixed $file パラメータ配列、BSFile、ファイルパス文字列
	 * @param string $class クラス名
	 * @return BSFile ファイル
	 * @static
	 */
	static public function search ($file, $class = 'BSFile') {
		$files = new BSFileFinder($class);
		if (is_array($file) || ($file instanceof BSParameterHolder)) {
			$params = new BSArray($file);
			if (BSString::isBlank($path = $params['src'])) {
				$records = new BSRecordFinder($params);
				if ($record = $records->execute()) {
					if ($attachment = $record->getAttachment($params['size'])) {
						return $files->execute($attachment);
					}
				}
			} else {
				return $files->execute($path);
			}
		} else {
			return $files->execute($file);
		}
	}
}

/* vim:set tabstop=4: */
