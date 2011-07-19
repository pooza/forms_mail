<?php
/**
 * @package org.carrot-framework
 * @subpackage media.image
 */

/**
 * 画像ファイル
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 */
class BSImageFile extends BSMediaFile implements BSImageContainer, BSAssignable {
	protected $renderer;
	protected $rendererClass;
	const DEFAULT_RENDERER_CLASS = 'BSImage';

	/**
	 * @access public
	 * @param string $path パス
	 * @param string $class レンダラーのクラス名
	 */
	public function __construct ($path, $class = self::DEFAULT_RENDERER_CLASS) {
		$this->setPath($path);
		$this->attributes = new BSArray;
		$this->rendererClass = $class;
	}

	/**
	 * @access public
	 * @param string $method メソッド名
	 * @param mixed[] $values 引数
	 */
	public function __call ($method, $values) {
		return BSUtility::executeMethod($this->getRenderer(), $method, $values);
	}

	/**
	 * リネーム
	 *
	 * @access public
	 * @param string $name 新しい名前
	 */
	public function rename ($name) {
		$name .= BSImage::getSuffixes()->getParameter($this->getEngine()->getType());
		parent::rename($name);
	}

	/**
	 * ファイルを解析
	 *
	 * @access protected
	 */
	protected function analyze () {
		$this->attributes['path'] = $this->getPath();
		$this->attributes['type'] = $this->getRenderer()->getType();
		$this->attributes['width'] = $this->getRenderer()->getWidth();
		$this->attributes['height'] = $this->getRenderer()->getHeight();
		$this->attributes['height_full'] = $this->getRenderer()->getHeight();
		$this->attributes['pixel_size'] = $this['width'] . '×' . $this['height'];
	}

	/**
	 * 削除
	 *
	 * @access public
	 */
	public function delete () {
		$this->clearImageCache();
		parent::delete();
	}

	/**
	 * レンダラーを返す
	 *
	 * @access public
	 * @return BSImageRenderer レンダラー
	 */
	public function getRenderer () {
		if (!$this->renderer) {
			if (!$this->isExists() || !$this->getSize()) {
				throw new BSImageException($this . 'の形式が不明です。');
			}
			$info = getimagesize($this->getPath());

			if ($this->rendererClass != 'BSImagickImage') {
				foreach (array('jpeg', 'gif', 'png') as $suffix) {
					if ($info['mime'] == BSMIMEType::getType($suffix)) {
						$class = BSClassLoader::getInstance()->getClass($this->rendererClass);
						$this->renderer = new $class($info[0], $info[1]);
						$this->renderer->setType($info['mime']);
						$function = 'imagecreatefrom' . $suffix;
						$this->renderer->setImage($function($this->getPath()));
						return $this->renderer;
					}
				}
			}
			if (extension_loaded('imagick')) {
				$this->renderer = new BSImagickImage;
				$this->renderer->setImagick(new Imagick($this->getPath()));
				return $this->renderer;
			}
			throw new BSImageException($this . 'の形式が不明です。');
		}
		return $this->renderer;
	}

	/**
	 * レンダラーを返す
	 *
	 * getRendererのエイリアス
	 *
	 * @access public
	 * @return BSImageRenderer レンダラー
	 * @final
	 */
	final public function getEngine () {
		return $this->getRenderer();
	}

	/**
	 * レンダラーを設定
	 *
	 * @access public
	 * @param BSImageRenderer $renderer レンダラー
	 */
	public function setRenderer (BSImageRenderer $renderer) {
		$this->renderer = $renderer;
		$this->rendererClass = get_class($renderer);
		$this->attributes = null;
	}

	/**
	 * レンダラーを設定
	 *
	 * setRendererのエイリアス
	 *
	 * @access public
	 * @param BSImageRenderer $renderer レンダラー
	 * @final
	 */
	final public function setEngine (BSImageRenderer $renderer) {
		$this->setRenderer($renderer);
	}

	/**
	 * 保存
	 *
	 * @access public
	 */
	public function save () {
		if ($this->isExists() && !$this->isWritable()) {
			throw new BSFileException($this . 'に書き込むことができません。');
		}

		$types = new BSArray;
		$types[] = BSMIMEType::DEFAULT_TYPE;
		$types[] = $this->getRenderer()->getType();
		if (!$types->isContain($this->getType())) {
			throw new BSImageException($this . 'のメディアタイプがレンダラーと一致しません。');
		}

		$this->clearImageCache();
		$this->setContents($this->getRenderer()->getContents());

		if (!BS_IMAGE_STORABLE) {
			$dir = BSFileUtility::getDirectory('image_magick');
			if ($dir->getEntry('bin/mogrify')) {
				$command = new BSCommandLine('bin/mogrify');
				$command->setDirectory($dir);
				$command->addValue('-comment', true);
				$command->addValue('kddi_copyright=on,copy="NO"');
				$command->addValue($this->getPath());
				$command->execute();
			}
		}
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

		$element = new BSImageElement;
		$element->setURL($this->createURL($params));
		$element->registerStyleClass($params['style_class']);
		$element->setAttribute('width', $this['width']);
		$element->setAttribute('height', $this['height']);
		$element->setAttributes($params);
		return $element;
	}

	/**
	 * キャッシュをクリア
	 *
	 * @access public
	 * @param string $size
	 */
	public function clearImageCache ($size = null) {
		$images = new BSImageManager;
		$images->removeThumbnail($this, $size);
	}

	/**
	 * 画像の情報を返す
	 *
	 * @access public
	 * @param string $size サイズ名
	 * @param integer $pixel ピクセルサイズ
	 * @param integer $flags フラグのビット列
	 * @return BSArray 画像の情報
	 */
	public function getImageInfo ($size = null, $pixel = null, $flags = null) {
		$images = new BSImageManager;
		return $images->getImageInfo($this, $size, $pixel, $flags);
	}

	/**
	 * 画像ファイルを返す
	 *
	 * @access public
	 * @param string $size サイズ名
	 * @return BSImageFile 画像ファイル
	 */
	public function getImageFile ($size = null) {
		return $this;
	}

	/**
	 * 画像ファイルを設定
	 *
	 * @access public
	 * @param BSImageFile $file 画像ファイル
	 * @param string $size サイズ名
	 */
	public function setImageFile (BSImageFile $file, $size = null) {
		$this->getEngine()->setImage($file);
		$this->save();
	}

	/**
	 * 画像ファイルベース名を返す
	 *
	 * @access public
	 * @param string $size サイズ名
	 * @return string 画像ファイルベース名
	 */
	public function getImageFileBaseName ($size) {
		return $this->getBaseName();
	}

	/**
	 * 出力可能か？
	 *
	 * @access public
	 * @return boolean 出力可能ならTrue
	 */
	public function validate () {
		if (!parent::validate()) {
			return false;
		}
		return (BSMIMEUtility::getMainType($this->analyzeType()) == 'image');
	}

	/**
	 * ラベルを返す
	 *
	 * @access public
	 * @param string $language 言語
	 * @return string ラベル
	 */
	public function getLabel ($language = 'ja') {
		try {
			return BSTranslateManager::getInstance()->execute(
				$this->getBaseName(),
				'user_image',
				$language
			);
		} catch (BSTranslateException $e) {
			return $this->getBaseName();
		}
	}

	/**
	 * アサインすべき値を返す
	 *
	 * @access public
	 * @return mixed アサインすべき値
	 */
	public function getAssignableValues () {
		$values = $this->getImageInfo();
		$values['path'] = $this->getPath();
		return $values;
	}

	/**
	 * @access public
	 * @return string 基本情報
	 */
	public function __toString () {
		return sprintf('画像ファイル "%s"', $this->getShortPath());
	}
}

/* vim:set tabstop=4: */
