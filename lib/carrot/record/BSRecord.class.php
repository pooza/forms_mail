<?php
/**
 * @package org.carrot-framework
 * @subpackage database.record
 */

/**
 * テーブルのレコード
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @abstract
 */
abstract class BSRecord implements ArrayAccess,
	BSSerializable, BSAssignable, BSAttachmentContainer, BSImageContainer, BSHTTPRedirector {

	private $attributes;
	private $table;
	private $criteria;
	private $records;

	/**
	 * @access public
	 * @param BSTableHandler $table テーブルハンドラ
	 * @param string[] $attributes 属性の連想配列
	 */
	public function __construct (BSTableHandler $table, $attributes = null) {
		$this->table = $table;
		$this->attributes = new BSArray;
		$this->records = new BSArray;
		if ($attributes) {
			$this->initialize($attributes);
		}
	}

	/**
	 * @access public
	 * @param string $method メソッド名
	 * @param mixed[] $values 引数
	 */
	public function __call ($method, $values) {
		if (mb_ereg('^get([[:upper:]][[:alnum:]]+)$', $method, $matches)) {
			$name = $matches[1];
			if (!$this->records->hasParameter($name)) {
				$table = BSTableHandler::getInstance($name);
				$this->records[$name] = $table->getRecord($this[$table->getName() . '_id']);
			}
			return $this->records[$name];
		} 

		$message = new BSStringFormat('仮想メソッド"%s"は未定義です。');
		$message[] = $method;
		throw new BadFunctionCallException($message);
	}

	/**
	 * 属性値を初期化
	 *
	 * @access public
	 * @param string[] $attributes 属性の連想配列
	 * @return BSRecord 自分自身
	 */
	public function initialize ($attributes) {
		$this->attributes->clear();
		$this->attributes->setParameters($attributes);
		return $this;
	}

	/**
	 * 属性を返す
	 *
	 * @access public
	 * @param string $name 属性名
	 * @return string 属性値
	 */
	public function getAttribute ($name) {
		return $this->attributes[BSString::toLower($name)];
	}

	/**
	 * 全属性を返す
	 *
	 * @access public
	 * @return BSArray 全属性値
	 */
	public function getAttributes () {
		return clone $this->attributes;
	}

	/**
	 * 抽出条件を返す
	 *
	 * @access protected
	 * @return BSCriteriaSet 抽出条件
	 */
	protected function getCriteria () {
		if (!$this->criteria) {
			$this->criteria = $this->createCriteriaSet();
			$this->criteria->register($this->getTable()->getKeyField(), $this);
		}
		return $this->criteria;
	}

	/**
	 * 更新
	 *
	 * @access public
	 * @param mixed $values 更新する値
	 * @param integer $flags フラグのビット列
	 *   BSDatabase::WITHOUT_LOGGING ログを残さない
	 *   BSDatabase::WITHOUT_SERIALIZE シリアライズしない
	 */
	public function update ($values, $flags = null) {
		if (!$this->isUpdatable()) {
			throw new BSDatabaseException($this . 'を更新することはできません。');
		}

		$values = new BSArray($values);
		$db = $this->getDatabase();
		$table = $this->getTable();
		$fields = $table->getProfile()->getFields();
		if ($fields[$table->getUpdateDateField()]) {
			$values[$table->getUpdateDateField()] = BSDate::getNow('Y-m-d H:i:s');
		}
		if (!$values->count()) {
			return;
		}

		$db->exec(BSSQL::getUpdateQueryString($table, $values, $this->getCriteria(), $db));
		if ($record = $this->getParent()) {
			$record->touch();
		}
		$this->attributes->setParameters($values);
		if ($this->isSerializable() && !($flags & BSDatabase::WITHOUT_SERIALIZE)) {
			BSController::getInstance()->removeAttribute($this);
		}
		if (!($flags & BSDatabase::WITHOUT_LOGGING)) {
			$this->getDatabase()->putLog($this . 'を更新しました。');
		}
	}

	/**
	 * 更新可能か？
	 *
	 * @access protected
	 * @return boolean 更新可能ならTrue
	 */
	protected function isUpdatable () {
		return false;
	}

	/**
	 * 更新日付のみ更新
	 *
	 * @access public
	 */
	public function touch () {
		$this->update(array(), BSDatabase::WITHOUT_LOGGING);
	}

	/**
	 * 削除
	 *
	 * @access public
	 * @param integer $flags フラグのビット列
	 *   BSDatabase::WITHOUT_LOGGING ログを残さない
	 */
	public function delete ($flags = null) {
		if (!$this->isDeletable()) {
			throw new BSDatabaseException($this . 'を削除することはできません。');
		}

		if (!$this->getDatabase()->hasForeignKey()) {
			foreach ($this->getTable()->getChildClasses() as $class) {
				$table = BSTableHandler::getInstance($class);
				$table->getCriteria()->register($this->getTable()->getName() . '_id', $this);
				foreach ($table as $record) {
					$record->delete();
				}
			}
		}
		if ($record = $this->getParent()) {
			$record->touch();
		}
		$this->getDatabase()->exec(
			BSSQL::getDeleteQueryString($this->getTable(), $this->getCriteria())
		);
		foreach ($this->getTable()->getImageNames() as $field) {
			if ($file = $this->getImageFile($field)) {
				$file->delete();
			}
			$this->clearImageCache($field);
		}
		foreach ($this->getTable()->getAttachmentNames() as $field) {
			if ($file = $this->getAttachment($field)) {
				$file->delete();
			}
		}
		BSController::getInstance()->removeAttribute($this);
		if (!($flags & BSDatabase::WITHOUT_LOGGING)) {
			$this->getDatabase()->putLog($this . 'を削除しました。');
		}
	}

	/**
	 * 削除可能か？
	 *
	 * @access protected
	 * @return boolean 削除可能ならTrue
	 */
	protected function isDeletable () {
		return false;
	}

	/**
	 * 表示して良いか？
	 *
	 * @access public
	 * @return boolean 表示して良いならTrue
	 */
	public function isVisible () {
		return ($this['status'] == 'show');
	}

	/**
	 * 生成元テーブルハンドラを返す
	 *
	 * @access public
	 * @return BSTableHandler テーブルハンドラ
	 */
	public function getTable () {
		return $this->table;
	}

	/**
	 * データベースを返す
	 *
	 * @access public
	 * @return BSDatabase データベース
	 */
	public function getDatabase () {
		return $this->getTable()->getDatabase();
	}

	/**
	 * 親レコードを返す
	 *
	 * 適切にオーバーライドすれば、update等の動作が少し利口に。
	 *
	 * @access public
	 * @return BSRecord 親レコード
	 */
	public function getParent () {
		return null;
	}

	/**
	 * 抽出条件を生成して返す
	 *
	 * @access protected
	 * @return BSCriteriaSet 抽出条件
	 */
	protected function createCriteriaSet () {
		return $this->getDatabase()->createCriteriaSet();
	}

	/**
	 * IDを返す
	 *
	 * @access public
	 * @return integer ID
	 */
	public function getID () {
		return $this[$this->getTable()->getKeyField()];
	}

	/**
	 * 更新日を返す
	 *
	 * @access public
	 * @return BSDate 更新日
	 */
	public function getUpdateDate () {
		return BSDate::getInstance($this[$this->getTable()->getUpdateDateField()]);
	}

	/**
	 * 作成日を返す
	 *
	 * @access public
	 * @return BSDate 作成日
	 */
	public function getCreateDate () {
		return BSDate::getInstance($this[$this->getTable()->getCreateDateField()]);
	}

	/**
	 * 添付ファイルの情報を返す
	 *
	 * @access public
	 * @param string $name 名前
	 * @return string[] 添付ファイルの情報
	 */
	public function getAttachmentInfo ($name = null) {
		if ($file = $this->getAttachment($name)) {
			$info = new BSArray;
			$info['path'] = $file->getPath();
			$info['size'] = $file->getSize();
			$info['type'] = $file->getType();
			$info['filename'] = $this->getAttachmentFileName($name);
			if ($url = $this->getAttachmentURL($name)) {
				$info['url'] = $url->getContents();
			}
			return $info;
		}
	}

	/**
	 * 添付ファイルを返す
	 *
	 * @access public
	 * @param string $name 名前
	 * @return BSFile 添付ファイル
	 */
	public function getAttachment ($name = null) {
		$finder = new BSFileFinder;
		$finder->clearDirectories();
		$finder->registerDirectory($this->getTable()->getDirectory());
		$finder->registerSuffixes(BSMIMEType::getAttachableSuffixes());
		return $finder->execute($this->getAttachmentBaseName($name));
	}

	/**
	 * 添付ファイルを設定
	 *
	 * @access public
	 * @param BSFile $file 添付ファイル
	 * @param string $name 名前
	 */
	public function setAttachment (BSFile $file, $name = null) {
		if ($old = $this->getAttachment($name)) {
			$old->delete();
		}
		$file->setMode(0666);
		$file->setBinary(true);
		$suffix = BSMIMEType::getSuffix($file->analyzeType());
		$file->rename($this->getAttachmentBaseName($name) . $suffix);
		$file->moveTo($this->getTable()->getDirectory());
	}

	/**
	 * 添付ファイルベース名を返す
	 *
	 * @access public
	 * @param string $name 名前
	 * @return string 添付ファイルベース名
	 */
	public function getAttachmentBaseName ($name) {
		return sprintf('%06d_%s', $this->getID(), $name);
	}

	/**
	 * 添付ファイルのダウンロード時の名を返す
	 *
	 * @access public
	 * @param string $name 名前
	 * @return string ダウンロード時ファイル名
	 */
	public function getAttachmentFileName ($name = null) {
		if ($file = $this->getAttachment($name)) {
			return $this->getAttachmentBaseName($name) . $file->getSuffix();
		}
	}

	/**
	 * 添付ファイルのURLを返す
	 *
	 * @access public
	 * @param string $name 名前
	 * @return BSURL 添付ファイルURL
	 */
	public function getAttachmentURL ($name = null) {
	}

	/**
	 * 添付ファイルをまとめて設定
	 *
	 * @access public
	 * @param BSWebRequest $request リクエスト
	 */
	public function setAttachments (BSWebRequest $request) {
		foreach ($this->getTable()->getImageNames() as $name) {
			if ($info = $request[$name]) {
				$this->setImageFile(new BSImageFile($info['tmp_name']), $name);
			}
		}
		foreach ($this->getTable()->getAttachmentNames() as $name) {
			if ($info = $request[$name]) {
				$this->setAttachment(new BSFile($info['tmp_name']), $name);
			}
		}
	}

	/**
	 * キャッシュをクリア
	 *
	 * @access public
	 * @param string $size
	 */
	public function clearImageCache ($size = 'thumbnail') {
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
	public function getImageInfo ($size = 'thumbnail', $pixel = null, $flags = null) {
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
	public function getImageFile ($size = 'thumbnail') {
		foreach (BSImage::getSuffixes() as $suffix) {
			$name = $this->getImageFileBaseName($size) . $suffix;
			if ($file = $this->getTable()->getDirectory()->getEntry($name, 'BSImageFile')) {
				return $file;
			}
		}
	}

	/**
	 * 画像ファイルを設定する
	 *
	 * @access public
	 * @param BSImageFile $file 画像ファイル
	 * @param string $size サイズ名
	 */
	public function setImageFile (BSImageFile $file, $size = 'thumbnail') {
		if ($old = $this->getImageFile($size)) {
			$old->delete();
		}
		$file->setMode(0666);
		$file->rename($this->getImageFileBaseName($size));
		$file->moveTo($this->getTable()->getDirectory());
		$this->clearImageCache($size);
	}

	/**
	 * 画像ファイルベース名を返す
	 *
	 * @access public
	 * @param string $size サイズ名
	 * @return string 画像ファイルベース名
	 */
	public function getImageFileBaseName ($size) {
		return sprintf('%06d_%s', $this->getID(), $size);
	}

	/**
	 * ラベルを返す
	 *
	 * @access public
	 * @param string $language 言語
	 * @return string ラベル
	 */
	public function getLabel ($language = 'ja') {
		foreach (array('name', 'label', 'title') as $name) {
			foreach (array(null, $this->getTable()->getName() . '_') as $prefix) {
				foreach (array(null, '_' . $language) as $suffix) {
					if (!BSString::isBlank($label = $this[$prefix . $name . $suffix])) {
						return $label;
					}
				}
			}
		}
	}

	/**
	 * ラベルを返す
	 *
	 * getLabelのエイリアス
	 *
	 * @access public
	 * @param string $language 言語
	 * @return string ラベル
	 * @final
	 */
	final public function getName ($language = 'ja') {
		return $this->getLabel($language);
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
		throw new BSDatabaseException('レコードの属性を直接更新することはできません。');
	}

	/**
	 * @access public
	 * @param string $key 添え字
	 */
	public function offsetUnset ($key) {
		throw new BSDatabaseException('レコードの属性は削除できません。');
	}

	/**
	 * リダイレクト対象
	 *
	 * @access public
	 * @return BSURL
	 */
	public function getURL () {
		if (BSString::isBlank($this['url'])) {
			$url = BSURL::getInstance(null, 'carrot');
			$url['module'] = 'User' . BSString::pascalize($this->getTable()->getName());
			$url['action'] = 'Detail';
			$url['record'] = $this;
		} else {
			$url = BSURL::getInstance($this['url']);
		}
		return $url;
	}

	/**
	 * リダイレクト
	 *
	 * @access public
	 * @return string ビュー名
	 */
	public function redirect () {
		return BSController::getInstance()->redirect($this);
	}

	/**
	 * シリアライズするか？
	 *
	 * @access public
	 * @return boolean シリアライズするならTrue
	 */
	public function isSerializable () {
		return false;
	}

	/**
	 * 属性名へシリアライズ
	 *
	 * @access public
	 * @return string 属性名
	 */
	public function serializeName () {
		return sprintf('%s.%08d', get_class($this), $this->getID());
	}

	/**
	 * シリアライズ
	 *
	 * @access public
	 */
	public function serialize () {
		if (!$this->isSerializable()) {
			throw new BSDatabaseException($this . 'はシリアライズできません。');
		}
		BSController::getInstance()->setAttribute($this, $this->getFullAttributes());
	}

	/**
	 * シリアライズ時の値を返す
	 *
	 * @access public
	 * @return mixed シリアライズ時の値
	 */
	public function getSerialized () {
		if ($date = $this->getUpdateDate()) {
			return BSController::getInstance()->getAttribute($this, $date);
		} else {
			return BSController::getInstance()->getAttribute($this);
		}
	}

	/**
	 * 全てのファイル属性
	 *
	 * @access protected
	 * @return BSArray ファイル属性の配列
	 */
	protected function getFullAttributes () {
		$values = $this->getAttributes();
		$values['_attributes'] = $this->getAttributes();
		if ($url = $this->getURL()) {
			$values['url'] = $url->getContents();
		}
		foreach ($this->getTable()->getImageNames() as $field) {
			$values['has_' . $field] = !!$this->getImageFile($field);
		}
		foreach ($this->getTable()->getAttachmentNames() as $field) {
			if ($this->getAttachment($field)) {
				$values['has_' . $field] = true;
				$values[$field] = $this->getAttachmentInfo($field);
			}
		}
		return $values;
	}

	/**
	 * アサインすべき値を返す
	 *
	 * @access public
	 * @return mixed アサインすべき値
	 */
	public function getAssignValue () {
		if ($this->isSerializable()) {
			if (BSString::isBlank($this->getSerialized())) {
				$this->serialize();
			}
			return $this->getSerialized();
		} else {
			return $this->getFullAttributes();
		}
	}

	/**
	 * @access public
	 * @return string 基本情報
	 */
	public function __toString () {
		try {
			$word = BSTranslateManager::getInstance()->execute($this->getTable()->getName());
		} catch (BSTranslateException $e) {
			$word = $this->getTable()->getName();
		}
		return sprintf('%s(%s)', $word, $this->getID());
	}
}

/* vim:set tabstop=4: */
