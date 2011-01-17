<?php
/**
 * @package org.carrot-framework
 * @subpackage service.twitter
 */

/**
 * Twitterアカウント
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSTwitterAccount.class.php 2382 2010-10-09 10:38:22Z pooza $
 */
class BSTwitterAccount
	implements BSImageContainer, BSSerializable, BSAssignable, BSHTTPRedirector {

	protected $id;
	protected $url;
	protected $profile;
	protected $tweets;
	protected $requestToken;
	protected $accessToken;
	private $oauth;
	private $service;
	private $record;

	/**
	 * @access public
	 * @param mixed $id ユーザーID,スクリーンネーム等
	 */
	public function __construct ($id) {
		$this->id = $id;

		if (!$this->getSerialized()) {
			$this->serialize();
		}
		$serialized = $this->getSerialized();

		$this->profile = new BSArray($serialized['profile']);
		$this->tweets = new BSArray;
		foreach ($serialized['tweets'] as $tweet) {
			$this->tweets[] = new BSArray($tweet);
		}

		if ($token = BSUser::getInstance()->getAttribute(get_class($this))) {
			$this->requestToken = new BSArray($token);
		}
	}

	/**
	 * @access public
	 * @param string $method メソッド名
	 * @param mixed[] $values 引数
	 */
	public function __call ($method, $values) {
		if (mb_ereg('^get([[:upper:]][[:alnum:]]+)$', $method, $matches)) {
			$name = BSString::underscorize($matches[1]);
			if (!BSString::isBlank($this->profile[$name])) {
				return $this->profile[$name];
			}
		} 
	}

	/**
	 * レコードを返す
	 *
	 * @access protected
	 * @return BSTwitterAccountEntry レコード
	 */
	protected function getRecord () {
		if (!$this->record) {
			$table = new BSTwitterAccountEntryHandler;
			foreach (array('id', 'screen_name') as $field) {
				$values = array($field => $this->id);
				if ($this->record = $table->getRecord($values)) {
					break;
				}
			}
		}
		return $this->record;
	}

	/**
	 * サービスへの接続を返す
	 *
	 * @access protected
	 * @return BSTwitterService サービス
	 */
	protected function getService () {
		if (!$this->service) {
			$this->service = new BSTwitterService;
			if ($oauth = $this->getOAuth()) {
				$this->service->setOAuth($oauth);
			}
		}
		return $this->service;
	}

	/**
	 * OAuthオブジェクトを返す
	 *
	 * @access protected
	 * @return TwitterOAuth
	 */
	protected function getOAuth () {
		if (!$this->oauth && ($token = $this->getAccessToken())) {
			BSUtility::includeFile('twitteroauth');
			$this->oauth = new TwitterOAuth(
				BS_SERVICE_TWITTER_CONSUMER_KEY,
				BS_SERVICE_TWITTER_CONSUMER_SECRET,
				$token['oauth_token'],
				$token['oauth_token_secret']
			);
		}
		return $this->oauth;
	}

	/**
	 * OAuth認証ページのURLを返す
	 *
	 * @access public
	 * @return BSHTTPURL 認証ページのURL
	 */
	public function getOAuthURL () {
		BSUtility::includeFile('twitteroauth');
		$oauth = new TwitterOAuth(
			BS_SERVICE_TWITTER_CONSUMER_KEY,
			BS_SERVICE_TWITTER_CONSUMER_SECRET
		);
		$this->requestToken = new BSArray($oauth->getRequestToken());
		BSUser::getInstance()->setAttribute(get_class($this), $this->requestToken);

		return BSURL::getInstance($oauth->getAuthorizeURL($this->requestToken['oauth_token']));
	}

	/**
	 * OAuthの認証済みアクセストークンを返す
	 *
	 * @access public
	 * @return BSArray 認証済みアクセストークン
	 */
	public function getAccessToken () {
		if (!$this->accessToken && ($record = $this->getRecord())) {
			$this->accessToken = new BSArray(array(
				'user_id' => $record['id'],
				'screen_name' => $record['screen_name'],
				'oauth_token' => $record['oauth_token'],
				'oauth_token_secret' => $record['oauth_token_secret'],
			));
		}
		return $this->accessToken;
	}

	/**
	 * OAuth認証
	 *
	 * @access public
	 * @param string $verifier 認証ページが返したトークン
	 */
	public function login ($verifier) {
		if (!$this->requestToken) {
			return false;
		}
		$this->logout();

		BSUtility::includeFile('twitteroauth');
		$oauth = new TwitterOAuth(
			BS_SERVICE_TWITTER_CONSUMER_KEY,
			BS_SERVICE_TWITTER_CONSUMER_SECRET,
			$this->requestToken['oauth_token'],
			$this->requestToken['oauth_token_secret']
		);
		$this->accessToken = new BSArray($oauth->getAccessToken($verifier));

		$table = new BSTwitterAccountEntryHandler;
		$values = array(
			'id' => $this->accessToken['user_id'],
			'screen_name' => $this->accessToken['screen_name'],
			'oauth_token' => $this->accessToken['oauth_token'],
			'oauth_token_secret' => $this->accessToken['oauth_token_secret'],
		);
		$table->createRecord($values);
	}

	/**
	 * ログアウト
	 *
	 * @access public
	 */
	public function logout () {
		if ($record = $this->getRecord()) {
			$record->delete();
		}
		$this->accessToken = new BSArray;
	}

	/**
	 * OAuthで認証されているか？
	 *
	 * @access public
	 * @return boolean 認証されていたらTrue
	 */
	public function isAuthenticated () {
		try {
			$response = $this->getService()->sendGET('/account/verify_credentials');
			return ($response->getStatus() == 200);
		} catch (BSHTTPException $e) {
			return false;
		}
	}

	/**
	 * 最近のつぶやきを返す
	 *
	 * @access public
	 * @return BSArray 最近のつぶやき
	 */
	public function getTweets () {
		return $this->tweets;
	}

	/**
	 * つぶやく
	 *
	 * @access public
	 * @param string $message メッセージ
	 * @return BSJSONRenderer 結果文書
	 */
	public function tweet ($message) {
		if ($message instanceof BSStringFormat) {
			$message = $message->getContents();
		}
		$response = $this->getService()->sendPOST(
			'/statuses/update',
			new BSArray(array('status' => $message))
		);
		$json = new BSJSONRenderer;
		$json->setContents($response->getRenderer()->getContents());

		BSLogManager::getInstance()->put($this . 'がツイートしました。', $this->getService());
		return $json;
	}

	/**
	 * プロフィールアイコン画像を返す
	 *
	 * @access public
	 * @return BSImage プロフィールアイコン画像
	 */
	public function getIcon () {
		try {
			$url = BSURL::getInstance($this->profile['profile_image_url']);
			$image = new BSImage;
			$image->setImage($url->fetch());
			$image->setType(BSMIMEType::getType('png'));
			return $image;
		} catch (BSHTTPException $e) {
			return null;
		} catch (BSImageException $e) {
			return null;
		}
	}

	/**
	 * キャッシュをクリア
	 *
	 * @access public
	 * @param string $size
	 */
	public function clearImageCache ($size = null) {
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
	public function getImageInfo ($size = 'icon', $pixel = null, $flags = null) {
		if ($file = $this->getImageFile()) {
			$images = new BSImageManager;
			$info = $images->getImageInfo($file, $size, $pixel, $flags);
			$info['alt'] = $this->getLabel();
			return $info;
		}
	}

	/**
	 * 画像ファイルを返す
	 *
	 * @access public
	 * @param string $size サイズ名
	 * @return BSImageFile 画像ファイル
	 */
	public function getImageFile ($size = 'icon') {
		$dir = BSFileUtility::getDirectory('twitter_account');
		if ($file = $dir->getEntry($this->getImageFileBaseName($size), 'BSImageFile')) {
			$date = BSDate::getNow()->setAttribute('hour', '-1');
			if (!$file->getUpdateDate()->isPast($date)) {
				return $file;
			}
			$file->clearImageCache($size);
			$file->delete();
		}

		if (!$icon = $this->getIcon()) {
			return null;
		}
		$file = BSFileUtility::getTemporaryFile('png', 'BSImageFile');
		$file->setEngine($icon);
		$file->save();
		$file->setName($this->getImageFileBaseName($size));
		$file->moveTo($dir);
		return $file;
	}

	/**
	 * 画像ファイルを設定する
	 *
	 * @access public
	 * @param BSImageFile $file 画像ファイル
	 * @param string $size サイズ名
	 */
	public function setImageFile (BSImageFile $file, $size = 'icon') {
		throw new BSServiceException($this . 'の画像ファイルを設定できません。');
	}

	/**
	 * 画像ファイルベース名を返す
	 *
	 * @access public
	 * @param string $size サイズ名
	 * @return string 画像ファイルベース名
	 */
	public function getImageFileBaseName ($size) {
		return sprintf('%010d_%s', $this->getID(), $size);
	}

	/**
	 * アカウントIDを返す
	 *
	 * @access public
	 * @return integer ID
	 */
	public function getID () {
		return (int)$this->profile['id'];
	}

	/**
	 * スクリーン名を返す
	 *
	 * @access public
	 * @return string スクリーン名
	 */
	public function getName () {
		return $this->profile['screen_name'];
	}

	/**
	 * コンテナのラベルを返す
	 *
	 * @access public
	 * @param string $language 言語
	 * @return string ラベル
	 */
	public function getLabel ($language = 'ja') {
		return $this->profile['name'];
	}

	/**
	 * 属性名へシリアライズ
	 *
	 * @access public
	 * @return string 属性名
	 */
	public function serializeName () {
		return get_class($this) . '.' . $this->id;
	}

	/**
	 * シリアライズ
	 *
	 * @access public
	 */
	public function serialize () {
		$response = $this->getService()->sendGET('/statuses/user_timeline/' . $this->id);
		$json = new BSJSONRenderer;
		$json->setContents($response->getRenderer()->getContents());

		$values = array('profile' => null, 'tweets' => array());
		if ($entries = $json->getResult()) {
			foreach ($entries as $entry) {
				$tweet = new BSArray($entry);
				if (!$values['profile']) {
					$values['profile'] = $tweet['user'];
				}
				$url = BSURL::getInstance('http://' . BSTwitterService::DEFAULT_HOST);
				$url['path'] = '/' . $tweet['user']['screen_name'] . '/status/' . $entry['id'];
				$tweet['url'] = $url->getContents();
				$tweet->removeParameter('user');
				$values['tweets'][] = $tweet->getParameters();
			}
		} else { //ツイートがひとつもない場合は、プロフィールを取得
			$response = $this->getService()->sendGET('/users/show/' . $this->id);
			$json->setContents($response->getRenderer()->getContents());
			$values['profile'] = $json->getResult();
		}
		BSController::getInstance()->setAttribute($this, $values);
	}

	/**
	 * シリアライズ時の値を返す
	 *
	 * @access public
	 * @return mixed シリアライズ時の値
	 */
	public function getSerialized () {
		$date = BSDate::getNow()->setAttribute('minute', '-' . BS_SERVICE_TWITTER_MINUTES);
		return BSController::getInstance()->getAttribute($this, $date);
	}

	/**
	 * アサインすべき値を返す
	 *
	 * @access public
	 * @return mixed アサインすべき値
	 */
	public function getAssignValue () {
		$values = clone $this->profile;
		$values['timeline_url'] = $this->getURL()->getContents();
		$values['tweets'] = $this->tweets;
		return $values;
	}

	/**
	 * リダイレクト対象
	 *
	 * @access public
	 * @return BSURL
	 */
	public function getURL () {
		if (!$this->url) {
			$this->url = BSURL::getInstance();
			$this->url['host'] = BSTwitterService::DEFAULT_HOST;
			$this->url['path'] = '/' . $this->getScreenName();
		}
		return $this->url;
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
	 * @access public
	 * @return string 基本情報
	 */
	public function __toString () {
		return sprintf('Twitterアカウント "%s"', $this->id);
	}
}

/* vim:set tabstop=4: */
