<?php
/**
 * @package org.carrot-framework
 * @subpackage console
 */

/**
 * プロセス関連のユーティリティ
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 */
class BSProcess {

	/**
	 * @access private
	 */
	private function __construct () {
	}

	/**
	 * 現在のプロセスIDを返す
	 *
	 * @access public
	 * @return integer プロセスID
	 * @static
	 */
	static public function getCurrentID () {
		return getmypid();
	}

	/**
	 * 現在のユーザー名を返す
	 *
	 * @access public
	 * @return string ユーザー名
	 * @static
	 */
	static public function getCurrentUser () {
		$user = BSController::getInstance()->getAttribute('USER');
		if (PHP_OS == 'Darwin') {
			$user = ltrim($user, '_');
		}
		return $user;
	}

	/**
	 * プロセス名からpidを返す
	 *
	 * @access public
	 * @param string $name プロセス名
	 * @return integer プロセスが存在するなら、そのpid
	 * @static
	 */
	static public function getID ($name) {
		$command = new BSCommandLine('bin/pgrep');
		$command->push($name);
		$command->setDirectory(BSFileUtility::getDirectory('proctools'));
		if ($command->hasError()) {
			$message = new BSStringFormat('実行時エラーです。(%s)');
			$message[] = $command->getContents();
			throw new BSConsoleException($message);
		}

		if ($result = $command->getResult()) {
			return (int)$result[0];
		}
	}

	/**
	 * pidは存在するか？
	 *
	 * @access public
	 * @param integer プロセスID
	 * @return boolean pidが存在するならTrue
	 * @static
	 */
	static public function isExists ($pid) {
		$command = new BSCommandLine('ps');
		$command->push('ax');
		if ($command->hasError()) {
			throw new BSConsoleException($command->getResult());
		}

		foreach ($command->getResult() as $line) {
			$fields = mb_split(' +', trim($line));
			if ($fields[0] == $pid) {
				return true;
			}
		}
		return false;
	}
}

/* vim:set tabstop=4: */
