<?php
/**
 * @package org.carrot-framework
 * @subpackage console
 */

/**
 * コマンドラインビルダー
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSCommandLine.class.php 2417 2010-10-31 07:09:27Z pooza $
 */
class BSCommandLine {
	private $params;
	private $pipes;
	private $command;
	private $directory;
	private $result;
	private $returnCode = 0;
	private $background = false;
	private $stderrRedirectable = false;
	private $sleepSeconds = 0;
	const WITH_QUOTE = 1;

	/**
	 * @access public
	 * @param string $command prefix以降のコマンドパス。 'bin/mysql'等。
	 */
	public function __construct ($command) {
		if (!$command) {
			throw new BSConsoleException('コマンド名が空です。');
		}
		$this->command = $command;
		$this->params = new BSArray;
		$this->pipes = new BSArray;
	}

	/**
	 * ディレクトリプレフィックスを返す
	 *
	 * @access public
	 * @return BSDirectory ディレクトリプレフィックス
	 */
	public function getDirectory () {
		return $this->directory;
	}

	/**
	 * ディレクトリプレフィックスを設定
	 *
	 * @access public
	 * @param BSDirectory $dir ディレクトリプレフィックス
	 */
	public function setDirectory (BSDirectory $dir) {
		if (!$dir->isExists()) {
			throw new BSConsoleException($dir . 'が存在しません。');
		}
		$this->directory = $dir;
	}

	/**
	 * 値を末尾に加える
	 *
	 * @access public
	 * @param string $value 値
	 * @param integer $flags フラグのビット列
	 *   self::WITH_QUOTE クォートする
	 */
	public function push ($value, $flags = self::WITH_QUOTE) {
		if ($flags & self::WITH_QUOTE) {
			$value =  self::quote($value);
		}
		$this->params[] = $value;
	}

	/**
	 * 値を末尾に加える
	 *
	 * pushのエイリアス
	 *
	 * @access public
	 * @param string $value 値
	 * @param integer $flags フラグのビット列
	 *   self::WITH_QUOTE クォートする
	 * @final
	 */
	final public function addValue ($value, $flags = self::WITH_QUOTE) {
		$this->push($value, $flags);
	}

	/**
	 * パイプを加える
	 *
	 * @access public
	 * @param BSCommandLine $pipe パイプ
	 */
	public function registerPipe (BSCommandLine $pipe) {
		$this->pipes[] = $pipe;
	}

	/**
	 * 実行後の待機秒数を設定
	 *
	 * @access public
	 * @param integer $seconds 秒数
	 */
	public function setSleepSeconds ($seconds) {
		$this->sleepSeconds = $seconds;
	}

	/**
	 * 実行されたか？
	 *
	 * @access public
	 * @return boolean 実行されたならTrue
	 */
	public function isExecuted () {
		return !!$this->result;
	}

	/**
	 * バックグラウンド実行か？
	 *
	 * @access public
	 * @return boolean バックグラウンド実行ならTrue
	 */
	public function isBackground () {
		return $this->background;
	}

	/**
	 * バックグラウンド実行を設定
	 *
	 * @access public
	 * @param boolean $mode バックグラウンド実行ならTrue
	 */
	public function setBackground ($mode = true) {
		$this->background = $mode;
	}

	/**
	 * stderrはリダイレクト可能か？
	 *
	 * @access public
	 * @return boolean バックグラウンド実行ならTrue
	 */
	public function isStderrRedirectable () {
		return $this->stderrRedirectable;
	}

	/**
	 * stderrをリダイレクト可能に設定
	 *
	 * @access public
	 * @param boolean $mode リダイレクト可能ならTrue
	 */
	public function setStderrRedirectable ($mode = true) {
		$this->stderrRedirectable = $mode;
	}

	/**
	 * コマンドを実行
	 *
	 * @access public
	 */
	public function execute () {
		exec($this->getContents(), $result, $this->returnCode);
		$this->result = new BSArray($result);

		if ($seconds = $this->sleepSeconds) {
			sleep($seconds);
		}
	}

	/**
	 * コマンドラインを返す
	 *
	 * @access public
	 * @return string コマンドライン
	 */
	public function getContents () {
		$contents = clone $this->params;

		if ($this->directory) {
			if (!$file = $this->directory->getEntry($this->command)) {
				throw new BSConsoleException($this->command . 'が見つかりません。');
			}
			$contents->unshift($file->getPath());
		} else {
			$contents->unshift($this->command);
		}

		foreach ($this->pipes as $pipe) {
			$contents[] = '|';
			$contents[] = $pipe->getContents();
		}

		if ($this->isBackground()) {
			$contents[] = '> /dev/null &';
		} else if ($this->isStderrRedirectable()) {
			$contents[] = '2>&1';
		}

		return $contents->join(' ');
	}

	/**
	 * 実行後の標準出力を返す
	 *
	 * @access public
	 * @return string 標準出力
	 */
	public function getResult () {
		if (!$this->isExecuted()) {
			$this->execute();
		}
		return $this->result;
	}

	/**
	 * 実行後の戻り値を返す
	 *
	 * @access public
	 * @return integer 戻り値
	 */
	public function getReturnCode () {
		if (!$this->isExecuted()) {
			$this->execute();
		}
		return $this->returnCode;
	}

	/**
	 * 実行後の戻り値は、エラーを含んでいたか？
	 *
	 * @access public
	 * @return boolean エラーを含んでいたらTrue
	 */
	public function hasError () {
		return !!$this->getReturnCode();
	}

	/**
	 * 引数をクォートして返す
	 *
	 * @access public
	 * @param string $value 引数
	 * @return string クォートされた引数
	 * @static
	 */
	static private function quote ($value) {
		return escapeshellarg($value);
	}
}

/* vim:set tabstop=4: */
