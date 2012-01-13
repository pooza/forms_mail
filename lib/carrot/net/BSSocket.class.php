<?php
/**
 * @package org.carrot-framework
 * @subpackage net
 */

/**
 * ソケット
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 */
class BSSocket {
	protected $host;
	protected $port;
	protected $protocol;
	protected $name;
	protected $client;
	protected $line;
	const LINE_BUFFER = 4096;
	const LINE_SEPARATOR = "\r\n";

	/**
	 * @access public
	 * @param mixed $host ホスト
	 * @param integer $port ポート
	 * @param string $protocol プロトコル
	 *   BSNetworkService::TCP
	 *   BSNetworkService::UDP
	 */
	public function __construct ($host, $port = null, $protocol = BSNetworkService::TCP) {
		if (!($host instanceof BSHost)) {
			$host = new BSHost($host);
		}
		$this->host = $host;

		if (!$port && BSString::isBlank($port = $this->getDefaultPort())) {
			throw new BSNetException('ポートが未定義です。');
		}
		$this->port = $port;

		$this->protocol = $protocol;
	}

	/**
	 * @access public
	 */
	public function __destruct () {
		if ($this->isOpened()) {
			$this->close();
		}
	}

	/**
	 * 名前を返す
	 *
	 * @access public
	 * @return string 名前
	 */
	public function getName () {
		if (!$this->name) {
			$host = new BSStringFormat('%s://%s:%s');
			$host[] = $this->getProtocol();
			$host[] = $this->getHost()->getName();
			$host[] = $this->getPort();
			$this->name = $host->getContents();
		}
		return $this->name;
	}

	/**
	 * ストリームを開く
	 *
	 * @access public
	 */
	public function open () {
		$error = null;
		$message = null;
		if (!$this->client = stream_socket_client($this->getName(), $error, $result)) {
			$message = new BSStringFormat('%sに接続できません。(%s)');
			$message[] = $this;
			$message[] = $result;
			throw new BSNetException($message);
		}
		stream_set_timeout($this->client, 1);
	}

	/**
	 * ストリームを閉じる
	 *
	 * @access public
	 */
	public function close () {
		if ($this->isOpened()) {
			fclose($this->client);
		}
		$this->client = null;
	}

	/**
	 * ストリームに1行書き込む
	 *
	 * @access public
	 * @param string $str 書き込む内容
	 */
	public function putLine ($str = '') {
		if (!$this->isOpened()) {
			$this->open();
		}
		fwrite($this->client, $str . self::LINE_SEPARATOR);
	}

	/**
	 * ストリームから1行読み込む
	 *
	 * @access public
	 * @param integer $length 一度に読み込む最大のサイズ
	 * @return string 読み込んだ内容
	 */
	public function getLine ($length = self::LINE_BUFFER) {
		if (!$this->isOpened()) {
			$this->open();
		} else if ($this->isEof()) {
			return '';
		}
		return $this->line = stream_get_line($this->client, $length);
	}

	/**
	 * 直前のレスポンスを返す
	 *
	 * @access public
	 * @return string 直前のレスポンス
	 */
	public function getPrevLine () {
		return $this->line;
	}

	/**
	 * ストリームの終端まで読んで返す
	 *
	 * @access public
	 * @return BSArray 読み込んだ内容
	 */
	public function getLines () {
		return BSString::explode(self::LINE_SEPARATOR, stream_get_contents($this->client));
	}

	/**
	 * 開かれているか？
	 *
	 * @access public
	 * @return boolean 開かれていたらtrue
	 */
	public function isOpened () {
		return is_resource($this->client);
	}

	/**
	 * ポインタがEOFに達しているか？
	 *
	 * @access public
	 * @return boolean EOFに達していたらtrue
	 */
	public function isEof () {
		return feof($this->client);
	}

	/**
	 * ホストを返す
	 *
	 * @access public
	 * @return BSHost ホスト
	 */
	public function getHost () {
		return $this->host;
	}

	/**
	 * ポート番号を返す
	 *
	 * @access public
	 * @return integer port
	 */
	public function getPort () {
		return $this->port;
	}

	/**
	 * プロトコル名を返す
	 *
	 * @access public
	 * @return string プロトコル名
	 */
	public function getProtocol () {
		return $this->protocol;
	}

	/**
	 * 規定のポート番号を返す
	 *
	 * @access public
	 * @return integer port
	 */
	public function getDefaultPort () {
		return null;
	}

	/**
	 * @access public
	 * @return string 基本情報
	 */
	public function __toString () {
		return sprintf('TCP/IPソケット "%s"', $this->getName());
	}
}

/* vim:set tabstop=4: */
