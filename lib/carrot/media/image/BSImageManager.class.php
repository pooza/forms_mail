<?php
/**
 * @package org.carrot-framework
 * @subpackage media.image
 */

/**
 * 画像マネージャ
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSImageManager.class.php 2445 2010-12-13 05:41:58Z pooza $
 */
class BSImageManager {
	private $useragent;
	private $type;
	private $flags = 0;
	const WIDTH_FIXED = 2;
	const HEIGHT_FIXED = 4;
	const WITHOUT_SQUARE = 8;
	const FORCE_GIF = 16;

	/**
	 * @access public
	 * @param mixed $flags フラグのビット列、又は配列
	 */
	public function __construct ($flags = null) {
		$this->setFlags($flags);
	}

	/**
	 * 対象UserAgentを返す
	 *
	 * @access public
	 * @return BSUserAgent 対象UserAgent
	 */
	public function getUserAgent () {
		if (!$this->useragent) {
			$this->setUserAgent(BSRequest::getInstance()->getUserAgent());
		}
		return $this->useragent;
	}

	/**
	 * 対象UserAgentを設定
	 *
	 * @access public
	 * @param BSUserAgent $useragent 対象UserAgent
	 */
	public function setUserAgent (BSUserAgent $useragent) {
		$this->useragent = $useragent;
	}

	/**
	 * 規定の最大幅を返す
	 *
	 * @access public
	 * @return integer 規定の最大幅
	 */
	public function getDefaultWidth () {
		return $this->getUserAgent()->getDisplayInfo()->getParameter('width');
	}

	/**
	 * 規定フラグを返す
	 *
	 * @access public
	 * @return integer フラグのビット列
	 */
	public function getFlags () {
		return $this->flags;
	}

	/**
	 * 規定のフラグを設定
	 *
	 * @access public
	 * @param mixed $flags フラグのビット列、又は配列
	 */
	public function setFlags ($flags) {
		if (BSString::isBlank($flags)) {
			return;
		} else if (is_numeric($flags)) {
			$this->setFlag($flags);
		} else {
			if (!BSArray::isArray($flags)) {
				$flags = BSString::explode(',', $flags);
			}
			foreach ($flags as $flag) {
				$this->setFlag($flag);
			}
		}
	}

	private function setFlag ($flag) {
		if (!is_numeric($flag)) {
			$constants = BSConstantHandler::getInstance();
			$value = BSString::toUpper($flag);
			if (BSString::isBlank($flag = $constants['BSImageManager::' . $value])) {
				$message = new BSStringFormat('BSImageManager::%sが未定義です。');
				$message[] = $value;
				throw new BSImageException($message);
			}
		}
		$this->flags |= $flag;
	}

	/**
	 * 画像のタイプを返す
	 *
	 * @access public
	 * @return string タイプ
	 */
	public function getType () {
		return $this->getUserAgent()->getDefaultImageType();
	}

	/**
	 * サムネイルのURLを返す
	 *
	 * @access public
	 * @param BSImageContainer $record 対象レコード
	 * @param string $size サイズ名
	 * @param integer $pixel ピクセル数
	 * @param integer $flags フラグのビット列
	 *   self::WIDTH_FIXED 幅固定
	 *   self::HEIGHT_FIXED 高さ固定
	 *   self::WITHOUT_SQUARE 正方形に整形しない
	 *   self::FORCE_GIF gif形式を強制
	 * @return BSURL URL
	 */
	public function getURL (BSImageContainer $record, $size, $pixel = null, $flags = null) {
		if (!$file = $this->getFile($record, $size, $pixel, $flags)) {
			return null;
		}

		$flags |= $this->flags;
		$url = BSFileUtility::getURL('image_cache');
		$url['path'] .= $this->getEntryName($record, $size) . '/' . $file->getName();
		return $url;
	}

	/**
	 * サムネイルを返す
	 *
	 * @access public
	 * @param BSImageContainer $record 対象レコード
	 * @param string $size サイズ名
	 * @param integer $pixel ピクセル数
	 * @param integer $flags フラグのビット列
	 *   self::WIDTH_FIXED 幅固定
	 *   self::HEIGHT_FIXED 高さ固定
	 *   self::WITHOUT_SQUARE 正方形に整形しない
	 *   self::FORCE_GIF gif形式を強制
	 * @return BSImage サムネイル
	 */
	public function getThumbnail (BSImageContainer $record, $size, $pixel, $flags = null) {
		$flags |= $this->flags;
		if (!$file = $this->getFile($record, $size, $pixel, $flags)) {
			return null;
		}
		try {
			return $file->getRenderer();
		} catch (BSImageException $e) {
			$file->delete();
			BSLogManager::getInstance()->put($file . 'を削除しました。');
		}
	}

	/**
	 * サムネイルを設定する
	 *
	 * @access public
	 * @param BSImageContainer $record 対象レコード
	 * @param string $size サイズ名
	 * @param integer $pixel ピクセル数
	 * @param mixed $contents サムネイルの内容
	 * @param integer $flags フラグのビット列
	 *   self::WIDTH_FIXED 幅固定
	 *   self::HEIGHT_FIXED 高さ固定
	 *   self::WITHOUT_SQUARE 正方形に整形しない
	 *   self::FORCE_GIF gif形式を強制
	 * @param BSImage サムネイル
	 */
	public function setThumbnail (BSImageContainer $record, $size, $pixel, $contents, $flags = null) {
		$flags |= $this->flags;
		$dir = $this->getEntryDirectory($record, $size);
		$name = $this->getFileName($record, $pixel, $flags);
		if ($flags & self::FORCE_GIF) {
			$dir->setDefaultSuffix('.gif');
		}
		if (!$file = $dir->getEntry($name, 'BSImageFile')) {
			$file = $dir->createEntry($name, 'BSImageFile');
			$file->setMode(0666);
		}
		$file->setEngine($this->convertImage($record, $pixel, $contents, $flags));
		$file->save();
		return $file->getRenderer();
	}

	/**
	 * サムネイルを削除する
	 *
	 * @access public
	 * @param BSImageContainer $record 対象レコード
	 * @param string $size サイズ名
	 */
	public function removeThumbnail (BSImageContainer $record, $size) {
		if ($dir = $this->getEntryDirectory($record, $size)) {
			$dir->delete();
		}
	}

	/**
	 * 画像の情報を返す
	 *
	 * @access public
	 * @param BSImageContainer $record 対象レコード
	 * @param string $size サイズ名
	 * @param integer $pixel ピクセル数
	 * @param integer $flags フラグのビット列
	 *   self::WITHOUT_BWORSER_CACHE クエリー末尾に乱数を加え、ブラウザキャッシュを無効にする
	 *   self::WIDTH_FIXED 幅固定
	 *   self::HEIGHT_FIXED 高さ固定
	 *   self::WITHOUT_SQUARE 正方形に整形しない
	 *   self::FORCE_GIF gif形式を強制
	 * @return BSArray 画像の情報
	 */
	public function getImageInfo (BSImageContainer $record, $size, $pixel = null, $flags = null) {
		try {
			$date = $record->getUpdateDate();
		} catch (Exception $e) {
			$date = null;
		}

		$flags |= $this->flags;
		$name = get_class($this) . '.' . BSCrypt::getDigest(array(
			get_class($record), $record->getID(), $size, $pixel, $flags,
		));
		$info = new BSArray;
		if ($values = BSController::getInstance()->getAttribute($name, $date)) {
			$info->setParameters($values);
		} else if ($image = $this->getThumbnail($record, $size, $pixel, $flags)) {
			$info['url'] = $this->getURL($record, $size, $pixel, $flags)->getContents();
			$info['width'] = $image->getWidth();
			$info['height'] = $image->getHeight();
			$info['alt'] = $record->getLabel();
			$info['type'] = $image->getType();
			$info['pixel_size'] = $info['width'] . '×' . $info['height'];
			BSController::getInstance()->setAttribute($name, $info);
		}
		return $info;
	}

	/**
	 * サムネイルファイルを返す
	 *
	 * @access private
	 * @param BSImageContainer $record 対象レコード
	 * @param string $size サイズ名
	 * @param integer $pixel ピクセル数
	 * @param integer $flags フラグのビット列
	 *   self::WIDTH_FIXED 幅固定
	 *   self::HEIGHT_FIXED 高さ固定
	 *   self::WITHOUT_SQUARE 正方形に整形しない
	 *   self::FORCE_GIF gif形式を強制
	 * @return BSFile サムネイルファイル
	 */
	private function getFile (BSImageContainer $record, $size, $pixel, $flags = null) {
		if (!$source = $record->getImageFile($size)) {
			return null;
		}

		$flags |= $this->flags;
		$dir = $this->getEntryDirectory($record, $size);
		$name = $this->getFileName($record, $pixel, $flags);
		if ($flags & self::FORCE_GIF) {
			$name .= '.gif';
		}
		if (!$file = $dir->getEntry($name, 'BSImageFile')) {
			$this->setThumbnail($record, $size, $pixel, $source, $flags);
			$file = $dir->getEntry($name, 'BSImageFile');
		}
		return $file;
	}

	/**
	 * サムネイルファイルのファイル名を返す
	 *
	 * @access private
	 * @param BSImageContainer $record 対象レコード
	 * @param integer $pixel ピクセル数
	 * @param integer $flags フラグのビット列
	 *   self::WIDTH_FIXED 幅固定
	 *   self::HEIGHT_FIXED 高さ固定
	 *   self::WITHOUT_SQUARE 正方形に整形しない
	 * @return BSFile サムネイルファイル
	 */
	private function getFileName (BSImageContainer $record, $pixel, $flags = null) {
		$flags |= $this->flags;
		$prefix = '';
		if (!$pixel && ($width = $this->getDefaultWidth())) {
			$prefix = 'w';
			$pixel = $width;
		} else if ($flags & self::WITHOUT_SQUARE) {
			$prefix = 's';
		} else if ($flags & self::WIDTH_FIXED) {
			$prefix = 'w';
		} else if ($flags & self::HEIGHT_FIXED) {
			$prefix = 'h';
		}
		return $prefix . sprintf('%04d', $pixel);
	}

	/**
	 * 画像を変換して返す
	 *
	 * @access private
	 * @param BSImageContainer $record 対象レコード
	 * @param integer $pixel ピクセル数
	 * @param mixed $contents サムネイルの内容
	 * @param integer $flags フラグのビット列
	 *   self::WIDTH_FIXED 幅固定
	 *   self::HEIGHT_FIXED 高さ固定
	 *   self::WITHOUT_SQUARE 正方形に整形しない
	 *   self::FORCE_GIF gif形式を強制
	 * @param BSImage サムネイル
	 */
	private function convertImage (BSImageContainer $record, $pixel, $contents, $flags = null) {
		$image = new BSImage;
		$image->setImage($contents);
		$flags |= $this->flags;
		if ($flags & self::FORCE_GIF) {
			$image->setType(BSMIMEType::getType('gif'));
		} else {
			$image->setType($this->getType());
		}

		if ($pixel) {
			if ($flags & self::WITHOUT_SQUARE) {
				if ($image->getAspect() < 1) {
					$image->resizeHeight($pixel);
				} else {
					$image->resizeWidth($pixel);
				}
			} else if ($flags & self::WIDTH_FIXED) {
				$image->resizeWidth($pixel);
			} else if ($flags & self::HEIGHT_FIXED) {
				$image->resizeHeight($pixel);
			} else {
				$image->resizeSquare($pixel);
			}
		} else if ($width = $this->getDefaultWidth()) {
			$image->resizeWidth($width);
		}
		return $image;
	}

	/**
	 * サムネイル名を生成して返す
	 *
	 * @access private
	 * @param BSImageContainer $record 対象レコード
	 * @param string $size サイズ名
	 * @return string サムネイル名
	 */
	private function getEntryName (BSImageContainer $record, $size) {
		return BSCrypt::getDigest(array(
			get_class($record),
			$record->getID(),
			$size
		));
	}

	/**
	 * サムネイルエントリーの格納ディレクトリを返す
	 *
	 * @access private
	 * @param BSImageContainer $record 対象レコード
	 * @param string $size サイズ名
	 * @return string サムネイル名
	 */
	private function getEntryDirectory (BSImageContainer $record, $size) {
		$name = $this->getEntryName($record, $size);
		if (!$dir = $this->getDirectory()->getEntry($name)) {
			$dir = $this->getDirectory()->createDirectory($name);
			$dir->setMode(0777);
		}

		$suffixes = BSImage::getSuffixes();
		$dir->setDefaultSuffix($suffixes[$this->getType()]);
		return $dir;
	}

	/**
	 * ディレクトリを返す
	 *
	 * @access private
	 * @param BSDirectory ディレクトリ
	 */
	private function getDirectory () {
		return BSFileUtility::getDirectory('image_cache');
	}

	/**
	 * 画像情報から、HTMLのimg要素を返す
	 *
	 * @access public
	 * @param BSArray $info getImageInfoで取得した画像情報
	 * @return BSXMLElement img要素
	 */
	public function getElement (BSArray $info) {
		$element = new BSImageElement;
		$element->setUserAgent($this->getUserAgent());
		$element->setAttributes($info);
		return $element;
	}

	/**
	 * パラメータ配列から画像コンテナを返す
	 *
	 * @access private
	 * @param BSParameterHolder $params パラメータ配列
	 * @return BSImageContainer 画像コンテナ
	 */
	public function getContainer (BSParameterHolder $params) {
		$params = new BSArray($params);

		if (!BSString::isBlank($path = $params['src'])) {
			$finder = new BSFileFinder('BSImageFile');
			if ($dir = $params['dir']) {
				$finder->registerDirectory($dir);
			}
			if ($file = $finder->execute($path)) {
				return $file;
			}
		}

		$finder = new BSRecordFinder($params);
		if (!($container = $finder->execute()) && ($class = $params['class'])) {
			$container = new $class($params['id']);
		}
		if ($container && ($container instanceof BSImageContainer)) {
			return $container;
		}
	}
}

/* vim:set tabstop=4: */
