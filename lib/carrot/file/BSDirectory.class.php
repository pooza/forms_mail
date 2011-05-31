<?php
/**
 * @package org.carrot-framework
 * @subpackage file
 */

/**
 * ディレクトリ
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 */
class BSDirectory extends BSDirectoryEntry implements IteratorAggregate {
	private $suffix;
	private $entries;
	private $url;
	private $zip;
	const SORT_ASC = 'asc';
	const SORT_DESC = 'dsc';
	const WITHOUT_DOTTED = 1;
	const WITHOUT_IGNORE = 2;
	const WITHOUT_ALL_IGNORE = 4;
	const WITH_RECURSIVE = 8;

	/**
	 * @access public
	 * @param string $path ディレクトリのパス
	 */
	public function __construct ($path) {
		$this->setPath($path);
		if (!is_dir($this->getPath())) {
			throw new BSFileException($this . 'を開くことができません。');
		}
	}

	/**
	 * パスを設定
	 *
	 * @access protected
	 * @param string $path パス
	 */
	protected function setPath ($path) {
		parent::setPath(rtrim($path, '/'));
	}

	/**
	 * 規定サフィックスを返す
	 *
	 * @access public
	 * @return string サフィックス
	 */
	public function getDefaultSuffix () {
		return $this->suffix;
	}

	/**
	 * 規定サフィックスを設定
	 *
	 * @access public
	 * @param string $suffix 
	 */
	public function setDefaultSuffix ($suffix) {
		$this->suffix = ltrim($suffix, '*');
		$this->entries = null;
	}

	/**
	 * エントリーの名前を返す
	 *
	 * 拡張子による抽出を行い、かつ拡張子を削除する。
	 *
	 * @access public
	 * @param integer $flags フラグのビット列
	 *   self::WITHOUT_DOTTED ドットファイルを除く
	 *   self::WITHOUT_IGNORE 無視ファイルを除く
	 * @return BSArray 抽出されたエントリー名
	 */
	public function getEntryNames ($flags = null) {
		$names = new BSArray;
		foreach ($this->getAllEntryNames() as $name) {
			if (($flags & self::WITHOUT_DOTTED) && BSFileUtility::isDottedName($name)) {
				continue;
			} else if (($flags & self::WITHOUT_IGNORE) && BSFileUtility::isIgnoreName($name)) {
				continue;
			}
			if (fnmatch('*' . $this->getDefaultSuffix(), $name)) {
				$names[] = basename($name, $this->getDefaultSuffix());
			}
		}
		return $names;
	}

	/**
	 * 全エントリーの名前を返す
	 *
	 * 拡張子に関わらず全てのエントリーを返す。
	 *
	 * @access public
	 * @return BSArray 全エントリー名
	 */
	public function getAllEntryNames () {
		if (!$this->entries) {
			$this->entries = new BSArray;
			$iterator = new DirectoryIterator($this->getPath());
			foreach ($iterator as $entry) {
				if (!$entry->isDot()) {
					$this->entries[] = $entry->getFilename();
				}
			}
			if ($this->getSortOrder() == self::SORT_DESC) {
				$this->entries->sort(BSArray::SORT_VALUE_DESC);
			} else {
				$this->entries->sort(BSArray::SORT_VALUE_ASC);
			}
		}
		return $this->entries;
	}

	/**
	 * エントリーを返す
	 *
	 * @access public
	 * @param string $name エントリーの名前
	 * @param string $class エントリーのクラス名
	 * @return BSDirectoryEntry ディレクトリかファイル
	 */
	public function getEntry ($name, $class = null) {
		if (BSString::isBlank($class)) {
			$class = $this->getDefaultEntryClass();
		}
		$class = BSClassLoader::getInstance()->getClass($class);

		$path = $this->getPath() . '/' . BSString::stripControlCharacters($name);
		if ($this->hasSubDirectory() && is_dir($path)) {
			return new BSDirectory($path);
		} else if (is_file($path)) {
			return new $class($path);
		} else if (is_file($path .= $this->getDefaultSuffix())) {
			return new $class($path);
		}
	}

	/**
	 * 新しく作ったエントリーを作って返す
	 *
	 * @access public
	 * @param string $name エントリーの名前
	 * $param string $class クラス名
	 * @return BSFile ファイル
	 */
	public function createEntry ($name, $class = null) {
		if (BSString::isBlank($class)) {
			$class = $this->getDefaultEntryClass();
		}

		$name = basename($name, $this->getDefaultSuffix());
		$path = $this->getPath() . '/' . $name . $this->getDefaultSuffix();

		$class = BSClassLoader::getInstance()->getClass($class);
		$file = new $class($path);
		$file->setContents(null);
		$this->entries = null;
		return $file;
	}

	/**
	 * コピー
	 *
	 * 再帰的にコピーを行う。ドットファイル等は対象に含まない。
	 *
	 * @access public
	 * @param BSDirectory $dir コピー先ディレクトリ
	 * @return BSFile コピーされたファイル
	 */
	public function copyTo (BSDirectory $dir) {
		$name = $this->getName();
		if ($dir->getPath() == $this->getDirectory()->getPath()) {
			while ($dir->getEntry($name)) {
				$name = BSString::increment($name);
			}
		}
		if (!$dest = $dir->getEntry($name)) {
			$dest = $dir->createDirectory($name);
		}
		foreach ($this as $entry) {
			$entry->copyTo($dest);
		}
		return $dest;
	}

	/**
	 * 削除
	 *
	 * @access public
	 */
	public function delete () {
		$this->clear();
		if (!rmdir($this->getPath())) {
			throw new BSFileException($this . 'を削除できません。');
		}
	}

	/**
	 * 全てのエントリを削除
	 *
	 * @access public
	 */
	public function clear () {
		$iterator = new DirectoryIterator($this->getPath());
		foreach ($iterator as $entry) {
			if ($entry->isDot() || !$entry->isWritable()) {
				continue;
			}
			$this->getEntry($entry)->delete();
		}
	}

	/**
	 * ドットファイル等を削除
	 *
	 * @access public
	 */
	public function clearIgnoreFiles () {
		foreach ($this as $entry) {
			$entry->clearIgnoreFiles();
		}
		parent::clearIgnoreFiles();
		$this->entries = null;
	}

	/**
	 * 古いファイルを削除
	 *
	 * @access public
	 * @param BSDate $date 基準日
	 */
	public function purge (BSDate $date = null) {
		if (!$date) {
			$date = BSDate::getNow()->setAttribute('month', '-1');
		}
		foreach ($this as $entry) {
			if ($entry->isIgnore() || $entry->isDotted()) {
				continue;
			}
			if ($entry->getUpdateDate()->isPast($date)) {
				$entry->delete();
			}
		}

		$message = new BSStringFormat('%s内の、%s以前のエントリーを削除しました。');
		$message[] = $this;
		$message[] = $date->format('Y/n/j');
		BSLogManager::getInstance()->put($message, $this);
	}

	/**
	 * 新規ディレクトリを作り、返す
	 *
	 * @access public
	 * @param string $name ディレクトリの名前
	 * $param string $class クラス名
	 * @return BSDirectory 作成されたディレクトリ
	 */
	public function createDirectory ($name, $class = 'BSDirectory') {
		$path = $this->getPath() . '/' . $name;
		if (file_exists($path)) {
			if (!is_dir($path)) {
				throw new BSFileException($path . 'と同名のファイルが存在します。');
			}
		} else {
			mkdir($path);
		}
		return new $class($path);
	}

	/**
	 * URLを返す
	 *
	 * BSFileUtility::createURLから呼ばれるので、こちらを利用すること。
	 *
	 * @access public
	 * @return BSHTTPURL URL
	 */
	public function getURL () {
		if (!$this->url) {
			$documentRoot = BSFileUtility::getPath('www');
			if (mb_ereg('^' . $documentRoot, $this->getPath())) {
				$this->url = BSURL::create();
				$this->url['path'] = str_replace($documentRoot, '', $this->getPath()) . '/';
			}
		}
		return $this->url;
	}

	/**
	 * ZIPアーカイブを返す
	 *
	 * @access public
	 * @param integer $flags フラグのビット列
	 *   self::WITHOUT_DOTTED ドットファイルを除く
	 *   self::WITHOUT_IGNORE 無視ファイルを除く
	 * @return BSZipArchive ZIPアーカイブ
	 */
	public function getArchive ($flags = self::WITHOUT_ALL_IGNORE) {
		if (!extension_loaded('zip')) {
			throw new BSFileException('zipモジュールがロードされていません。');
		}
		if (!$this->zip) {
			$this->zip = new BSZipArchive;
			$this->zip->open(null, BSZipArchive::OVERWRITE);
			foreach ($this as $entry) {
				$this->zip->register($entry, null, $flags);
			}
			$this->zip->close();
		}
		return $this->zip;
	}

	/**
	 * ファイルモード（パーミッション）を設定
	 *
	 * @access public
	 * @param integer $mode ファイルモード
	 * @param integer $flags フラグのビット列
	 *   self::WITH_RECURSIVE 再帰的に
	 */
	public function setMode ($mode, $flags = null) {
		parent::setMode($mode);
		if ($flags & self::WITH_RECURSIVE) {
			foreach ($this as $entry) {
				$entry->setMode($mode, $flags);
			}
		}
	}

	/**
	 * @access public
	 * @return BSDirectoryIterator イテレータ
	 */
	public function getIterator () {
		return new BSDirectoryIterator($this);
	}

	/**
	 * サブディレクトリを持つか？
	 *
	 * @access public
	 * @return boolean サブディレクトリを持つならTrue
	 */
	public function hasSubDirectory () {
		return true;
	}

	/**
	 * エントリーのクラス名を返す
	 *
	 * @access public
	 * @return string エントリーのクラス名
	 */
	public function getDefaultEntryClass () {
		return 'BSFile';
	}

	/**
	 * ソート順を返す
	 *
	 * @access public
	 * @return string (ソート順 self::SORT_ASC | self::SORT_DESC)
	 */
	public function getSortOrder () {
		return self::SORT_ASC;
	}

	/**
	 * @access public
	 * @return string 基本情報
	 */
	public function __toString () {
		return sprintf('ディレクトリ "%s"', $this->getShortPath());
	}
}

/* vim:set tabstop=4: */
