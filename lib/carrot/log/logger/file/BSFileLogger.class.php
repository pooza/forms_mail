<?php
/**
 * @package org.carrot-framework
 * @subpackage log.logger.file
 */

/**
 * ファイル用ロガー
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSFileLogger.class.php 1812 2010-02-03 15:15:09Z pooza $
 */
class BSFileLogger extends BSLogger {
	private $dates;
	private $entries;
	private $file;

	/**
	 * @access public
	 */
	public function __destruct () {
		$this->file->close();
	}

	/**
	 * 初期化
	 *
	 * @access public
	 * @return string 利用可能ならTrue
	 */
	public function initialize () {
		try {
			$name = BSDate::getNow('Y-m-d');
			if (!$this->file = $this->getDirectory()->getEntry($name)) {
				$this->file = $this->getDirectory()->createEntry($name);
				$this->file->setMode(0666);
			}
			$this->file->open('a');
			return true;
		} catch (BSFileException $e) {
			return false;
		}
	}

	/**
	 * ログディレクトリを返す
	 *
	 * @access public
	 * @return BSLogDirectory ログディレクトリ
	 */
	public function getDirectory () {
		return BSFileUtility::getDirectory('log');
	}

	/**
	 * ログを出力
	 *
	 * @access public
	 * @param mixed $message ログメッセージ又は例外
	 * @param string $priority 優先順位
	 */
	public function put ($message, $priority = self::DEFAULT_PRIORITY) {
		if ($message instanceof Exception) {
			if ($priority == self::DEFAULT_PRIORITY) {
				$priority = $message->getName();
			}
			$message = $message->getMessage();
		}
		$this->file->putLine(BSLogManager::formatMessage($message, $priority));
	}

	/**
	 * 日付の配列を返す
	 *
	 * @access public
	 * @return BSArray 日付の配列
	 */
	public function getDates () {
		if (!$this->dates) {
			$this->dates = new BSArray;
			foreach ($this->getDirectory() as $file) {
				if (!$date = BSDate::getInstance($file->getBaseName())) {
					continue;
				}
				$month = $date->format('Y-m');
				if (!$this->dates[$month]) {
					$this->dates[$month] = new BSArray;
				}
				$this->dates[$month][$date->format('Y-m-d')] = $date->format('Y-m-d(ww)');
			}
		}
		return $this->dates;
	}

	/**
	 * エントリーを抽出して返す
	 *
	 * @access public
	 * @param string BSDate 対象日付
	 * @return BSArray エントリーの配列
	 */
	public function getEntries (BSDate $date) {
		if (!$this->entries) {
			$this->entries = new BSArray;
			if ($month = $this->getDates()->getParameter($date->format('Y-m'))) {
				if ($month->hasParameter($name = $date->format('Y-m-d'))) {
					$file = $this->getDirectory()->getEntry($name);
					$this->entries->setParameters($file->getEntries());
				}
			}
		}
		return $this->entries;
	}
}

/* vim:set tabstop=4: */
