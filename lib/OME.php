<?php
/**
###########OME.php/The character set of this file is UTF-8################
#
#  OME( Open Mail Envrionment ) for PHP   http://mac-ome.jp
#  by Masayuki Nii ( msyk@msyk.net )
@package OME
*/
/**
 *	このクラスは、日本語での正しいメール送信を行うために作ったものです。
 *	解説は、http://mac-ome.jp/site/php.html を参照してください。
 *
 *	history
*	<ul>
 *	<li>2003/7/23 「メール送信システムの作り方大全」のサンプルとして制作</li>
 *	<li>2003/9/13 OMEのフリーメール用に少しバージョンアップ</li>
 *	<li>2004/3/26 クラス化した。OMEとして公開する事にした。</li>
 *	<li>2004/4/18 バグフィックス</li>
 *	<li>2004/4/27 バグフィックス（BccやCcができなかったのを修正）</li>
 *	<li>2008/6/6 phpdocumentor向けにコメントを整理、パラメータ設定のメソッドを追加、ファイルをUTF-8にした</li>
 *	</ul>

* @package OME
* @author Masayuki Nii <msyk@msyk.net>
* @since PHP 4.0
* @version 
*/
class OME	{

var $body='';
var $subject='';
var $toField='';
var $ccField='';
var $bccField='';
var $fromField='';
var $extHeaders='';
var $errorMessage='';
var $sendmailParam='';
var $tmpContents = '';
var $bodyWidth = 74;

/**	エラーメッセージを取得する。
 *
 *	このクラスの多くの関数は、戻り値がbooleanとなっていて、それをもとにエラーかどうかを判別すできる。
 *	戻り値がfalseである場合、この関数を使ってエラーメッセージを取得できる。
 *
 *	@return string 日本語のエラーメッセージの文字列
 */
function getErrorMessage()	{
	return $this->errorMessage;
}

/**	メールの本文を設定する。既存の本文は置き換えられる。
 *
 *	@param string メールの本文に設定する文字列
 */
function setBody($str)	{
	$this->body = $str;
}

/**	メールの本文を追加する。既存の本文の後に追加する。
 *
 *	@param string メールの本文に追加する文字列
 */
function appendBody($str)	{
	$this->body .= $str;
}

/**	メールの件名を設定する。
 *
 *	@param string メールの件名に設定する文字列
 */
function setSubject($str)	{
	$this->subject = $str;
}

/**	追加のヘッダを1つ設定する。ただし、Subject、To、From、Cc、Bccは該当するメソッドを使う
 *
 *	@param string	追加するヘッダのフィールド
 *	@param string	フィールドの値。日本語を含める場合は自分でエンコードを行う
 */
function setExtraHeader($field, $value)	{
	$this->extHeaders = "$fields: $value\n";
}

/**	sendmailコマンドに与える追加のパラメータを指定する
 *
 *	@param string	追加のパラメータ。この文字列がそのままmb_send_mail関数の5つ目の引数となる
 */
function setSendMailParam( $param )	{
	$this->sendmailParam = $param;
}

/**	メールアドレスが正しい形式かどうかを判断する。
 *
 *	判断に使う正規表現は「^([a-z0-9_]|\-|\.)+@(([a-z0-9_]|\-)+\.)+[a-z]+$」なので、完全ではないが概ねOKかと。
 *
 *	@return	boolean	正しい形式ならTRUE、そうではないときはFALSE
 *	@param	string	チェックするメールアドレス。
 */
function checkEmail($address)	{
	if( ! mb_eregi ("^([a-z0-9_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]+$", $address) )	{
		$this->errorMessage = "アドレス“$address”は正しくないメールアドレスです。";
		return false;
	} else {
		return true;
	}
}

/**	Fromフィールドを設定する。

 *	@return	boolean	与えたメールアドレスが正しく、引数が適切に利用されればTRUEを返す。メールアドレスが正しくないとFALSEを戻し、内部変数等には与えた引数のデータは記録されない
 *	@param	string	送信者のアドレスで、アドレスとして正しいかどうかがチェックされる
 *	@param	string	送信者名（日本語の文字列はそのまま指定可能）で、省略しても良い
 *	@param	boolean	送信者アドレスを自動的にsendmailの-fパラメータとして与えて、Return-Pathのアドレスとして使用する場合はTRUE。既定値はFALSE
*/
function setFromField($address, $name='', $isSetToParam = FALSE )	{
	if ( $this->checkEmail($address) )	{
		if ( $name == '' )	{
			$this->fromField = $address;
			if ( $isSetToParam )
				$this->sendmailParam = "-f $address";
		}
		else	{
			$this->fromField = "$name <$address>";
			if ( $isSetToParam )
				$this->sendmailParam = "-f $address";
		}
		return true;
	}
	return false;
}

/**	Toフィールドを設定する。すでに設定されていれば上書きされ、この引数の定義だけが残る
 *
 *	@return	boolean	与えたメールアドレスが正しく、引数が適切に利用されればTRUEを返す。メールアドレスが正しくないとFALSEを戻し、内部変数等には与えた引数のデータは記録されない
 *
 *	@param string 送信者のアドレス
 *	@param string 送信者名
*/
function setToField($address, $name='')	{
	if ( $this->checkEmail($address) )	{
		if ( $name == '' )
			$this->toField = "$address";
		else
			$this->toField = "$name <$address>";
		return true;
	}
	return false;
}

/**	Toフィールドに追加する。
 *
 *	@return boolean メールアドレスを調べて不正ならfalse（アドレスは追加されない）、そうでなければtrue
 *	@param string	送信者のアドレス
 *	@param string	送信者名。日本語の指定も可能
*/
function appendToField($address, $name='')	{
	if ( $this->checkEmail($address) )	{
		if ( $name == '' )
			$appendString = "$address";
		else
			$appendString = "$name <$address>";
		if ( $this->toField == '' )
			$this->toField = $appendString;
		else
			$this->toField .= ", $appendString";
		return true;
	}
	return false;
}

/**	Ccフィールドを設定する。すでに設定されていれば上書きされ、この引数の定義だけが残る
 *
 *	@param string	送信者のアドレス
 *	@param string	送信者名
 *	@return boolean	メールアドレスを調べて不正ならfalse（アドレスは設定されない）、そうでなければtrue
*/
function setCcField($address, $name='')	{
	if ( $this->checkEmail($address) )	{
		if ( $name == '' )
			$this->ccField = "$address";
		else
			$this->ccField = "$name <$address>";
		return true;
	}
	return false;
}

/**	Ccフィールドに追加する。
 *
 *	@param string 送信者のアドレス
 *	@param string 送信者名
 *	@return boolean	メールアドレスを調べて不正ならfalse（アドレスは追加されない）、そうでなければtrue
*/
function appendCcField($address, $name='')	{
	if ( $this->checkEmail($address) )	{
		if ( $name == '' )
			$appendString = "$address";
		else
			$appendString = "$name <$address>";
		if ( $this->ccField == '' )
			$this->ccField = $appendString;
		else
			$this->ccField .= ", $appendString";
		return true;
	}
	return false;
}

/**	Bccフィールドを設定する。すでに設定されていれば上書きされ、この引数の定義だけが残る
 *
 *	@param string 送信者のアドレス
 *	@param string 送信者名
 *	@return boolean メールアドレスを調べて不正ならfalse（アドレスは設定されない）、そうでなければtrue
*/
function setBccField($address, $name='')	{
	if ( $this->checkEmail($address) )	{
		if ( $name == '' )
			$this->bccField = "$address";
		else
			$this->bccField = "$name <$address>";
		return true;
	}
	return false;
}

/**	Bccフィールドに追加する。
 *
 *	@param string 送信者のアドレス
 *	@param string 送信者名
 *	@return string メールアドレスを調べて不正ならfalse（アドレスは追加されない）、そうでなければtrue
*/
function appendBccField($address, $name='')	{
	if ( $this->checkEmail($address) )	{
		if ( $name == '' )
			$appendString = "$address";
		else
			$appendString = "$name <$address>";
		if ( $this->bccField == '' )
			$this->bccField = $appendString;
		else
			$this->bccField .= ", $appendString";
		return true;
	}
	return false;
}

/**	指定したファイルをテンプレートとして読み込む。
 *
 *	@param string テンプレートファイル。たとえば、同一のディレクトリにあるファイルなら、ファイル名だけを記述すればよい。
 *	@return boolean ファイルの中身を読み込めた場合true、ファイルがないなどのエラーの場合はfalse
*/
function setTemplateAsFile($tfile)	{
	$fileContensArray = file( $tfile );
	if ( $fileContensArray )	{
		$this->tmpContents = implode ( '', $fileContensArray );
		return true;
	}
	$this->errorMessage = "テンプレートファイルが存在しません。";
	return false;
}

/**	文字列そのものをテンプレートして設定する。
 *
 *	@param string	テンプレートとして利用する文字列
*/
function setTemplateAsString($str)	{
	$this->tmpContents = $str;
}

/**	テンプレートに引数の配列の内容を差し込み、それをメールの本文とする。既存の本文は上書きされる。
 *
 *	テンプレート中の「@@1@@」が、$ar[0]の文字列と置き換わる。
 *	テンプレート中の「@@2@@」が、$ar[1]の文字列と置き換わる。といった具合に置換する。
 *
 *	たとえば、配列の要素が5の場合、「@@6@@」や「@@7@@」などがテンプレート中に残るが、
 *	これらは差し込みをしてから強制的に削除される。強制削除があった場合にはfalseを戻すが、
 *	それでも差し込み自体は行われている。
 *
 *	@param array テンプレートに差し込むデータが入っている配列
 *	@return boolean 差し込み処理が問題なく終わればtrue、そうでなければfalse（たとえばテンプレートに "@@x@@" などの置き換え文字列が残っている場合。それでも可能な限り置き換えを行い、置き換え文字列は削除される）
*/
function insertToTemplate($ar)	{
	$counter = 1;
	foreach ( $ar as $aItem )	{
		$this->tmpContents = str_replace( "@@$counter@@", $aItem, $this->tmpContents );
		$counter += 1;
	}
	if ( ! mb_ereg( '@@[0-9]*@@', $tmpContents ) )	{
		$this->body = mb_ereg_replace('@@[0-9]*@@', '', $this->tmpContents);
		$this->errorMessage = '差し込みテンプレートに余分が置き換え文字列（@@数字@@）がありましたが、削除しました。';
		return false;
	}
	$this->body = $this->tmpContents;
	return true;
}

/**	本文の自動改行のバイト数を設定する。初期値は74になっている。
 *
 *	@param integer 改行を行うバイト数。0を指定すると自動改行しない。
 */
function setBodyWidth($bytes)	{
	$this->bodyWidth = $bytes;
}
/**	文字列中にコントロールコードが含まれているかを調べる
 *
 *	@param string 調べる文字列
 *	@return boolean 含まれていたらTRUEを返す
 */
function checkControlCodeNothing( $str )	{
	return mb_ereg('[[:cntrl:]]', $str);
}

/**	メールを送信する。
 *
 *	念のため、To、Cc、Bccのデータにコントロールコードが入っているかどうかをチェックしている。
 *	コントロールコードが見つかればfalseを返し送信はしないものとする。
 *
 *	@return boolean メールが送信できればtrue、送信できなければFALSE
*/
function send()	{
	if ( $this->checkControlCodeNothing ( $this->toField ) )	{
		$this->errorMessage = '宛先の情報にコントロールコードが含まれています。';
		return false;
	}
	if ( $this->checkControlCodeNothing ( $this->ccField ) )	{
		$this->errorMessage = '宛先の情報にコントロールコードが含まれています。';
		return false;
	}
	if ( $this->checkControlCodeNothing ( $this->bccField ) )	{
		$this->errorMessage = '宛先の情報にコントロールコードが含まれています。';
		return false;
	}
	$headerField = "X-Mailer: Open Mail Envrionment for PHP (http://mac-ome.jp/)\n";
	$headerField .= "Content-Type: text/plain; charset=ISO-2022-JP\n";
	if ( $this->fromField != '' )
		$headerField .= "From: $this->fromField\n";
	if ( $this->ccField != '' )
		$headerField .= "Cc: $this->ccField\n";
	if ( $this->bccField != '' )
		$headerField .= "Bcc: $this->bccField\n";
	if (  $this->extHeaders != '' )
		$headerField .= $this->extHeaders;

	if ( $this->sendmailParam != '' )	{
		$resultMail = mail(
			rtrim($this->header_base64_encode( $this->toField, False )),
			rtrim($this->header_base64_encode( $this->subject, true )),
			mb_convert_encoding( 
				$this->devideWithLimitingWidth( $this->body ), 'ISO-2022-JP' ),
			$this->header_base64_encode( $headerField, True ),
			$this->sendmailParam );
	} else {
		$resultMail = mail(
			rtrim($this->header_base64_encode( $this->toField, False )),
			rtrim($this->header_base64_encode( $this->subject, true )),
			mb_convert_encoding( 
				$this->devideWithLimitingWidth( $this->body ), 'ISO-2022-JP' ),
			$this->header_base64_encode( $headerField, True ) );
	}
	return $resultMail;
}

/**	文字列を別メソッドで決められたバイト数ごとに分割する。ワードラップ、禁則を考慮する。（内部利用メソッド）
 *
 *	@param string 処理対象の文字列
 *	@return string 分割された文字列
 */
function devideWithLimitingWidth($str)    {
	if ( $this->bodyWidth == 0 )
		return $str;
	$newLine = "\n";
	$strLength = mb_strlen($str);
	$devidedStr = mb_substr( $str, $pos, 1 );
	$beforeChar = $devidedStr;
	if( $devidedStr == $newLine )
	    $byteLength = 0;
	else
	    $byteLength = strlen( $devidedStr );
	for( $pos = 1 ; $pos < $strLength ; $pos++){
	    $posChar = mb_substr( $str, $pos, 1 );
	    if( $posChar == $newLine )
	        $byteLength = 0;
	    else  {
	        if(        ( $byteLength >= $this->bodyWidth )
	               &&  ! $this->isInhibitLineTopChar( $posChar )
	               &&  ! $this->isInhibitLineEndChar( $beforeChar ) ) {
	    
	            if(        (    $this->isJapanese( $posChar )
	                         && ! $this->isSpace( $posChar )     )
	                    || (    $this->isJapanese( $beforeChar )
	                         && $this->isWordElement( $posChar ) )
	                    || (    ! $this->isWordElement( $beforeChar ) 
	                         && $this->isWordElement( $posChar ) ) ) {
	    
	                $devidedStr .= $newLine;
	                $byteLength = 0;
	            }       // Endo of if
	        }
	        $byteLength += strlen( $posChar );
	    }
	    $devidedStr .= $posChar ;
	    $beforeChar = $posChar;
	}
	return $devidedStr;
}    // End of function devideWithLimitingWidth()

/**	引数の文字が空白かどうかのチェックを行う。ただ、これは標準の関数を利用すべきかもしれない（内部利用メソッド／devideWithLimitingWidth関数で利用）
 *
 *	@param string 処理対象の文字
 *	@return boolean 空白ならTRUE
 */
function isSpace( $str )    {
	switch( $str )    {
	    case " ":
	    case "｡｡":    return True;
	}       // Endo of switch
	return False;
}    // End of isSpace()
	
/**	引数の文字が単語を構成する文字（アルファベット、あるいは数値）かどうかのチェックを行う（内部利用メソッド／devideWithLimitingWidth関数で利用）
 *
 *	@param string 処理対象の文字
 *	@return boolean 単語を構成する文字ならTRUE
 */
function isWordElement( $str )    {
	if ( $this->isSpace( $str ) )    return False;
	$cCode = ord( $str );
	if ( ( $cCode >=0x30 ) && ($cCode <= 0x39) )    return True;
	if ( ( $cCode >=0x41 ) && ($cCode <= 0x5A) )    return True;
	if ( ( $cCode >=0x61 ) && ($cCode <= 0x7A) )    return True;
	switch( $str )    {
	    case "'":    return True;
	}       // Endo of switch
	return False;
}    // End of function isWordElement()

/**	引数が日本語の文字列かどうかを判断する（内部利用メソッド／devideWithLimitingWidth関数で利用）
 *
 *	@param string 処理対象の文字
 *	@return boolean 日本語ならTRUE
 */
function isJapanese( $str )    {
	$cCode = ord( $str );
	if ( $cCode >=0x80 )    return True;
	return False;
}    // End of function isJapanese()

/**	引数が日本語の行頭禁則文字かどうかを判断する（内部利用メソッド／devideWithLimitingWidth関数で利用）
 *
 *	@param string 処理対象の文字
 *	@return boolean 行頭禁則文字ならTRUE
 */
function isInhibitLineTopChar( $str )    {
	switch( $str )    {
	    case ')':    case ']':    case '}':     case '）':    case '】':
	    case '”':   case '］':   case '」':    case '』':    case '〕':
	    case '｝':   case '〉':   case '》':    case "’":    case '”':
	    case ':':    case ';':    case '!':     case '.':     case '?': 
	    case '。':   case '、':   case '，':    case '…':    case '‥':
	    case '．':   case '：':   case '；':    case '！':    case '？':
	        return True;
	}       // Endo of switch
	return False;
}    // End of function isInhibitLineTopChar

/**	引数が日本語の行末禁則文字かどうかを判断する（内部利用メソッド／devideWithLimitingWidth関数で利用）
 *
 *	@param string 処理対象の文字
 *	@return boolean 行末禁則文字ならTRUE
 */
function isInhibitLineEndChar( $str )    {
	switch( $str )    {
	    case '(':     case '[':     case '{':     case '（':    case '“':
	    case '【':    case '［':    case '『':    case '「':    case '〔':
	    case '｛':    case '〈':    case '《':    case "‘":
	        return True;
	}       // Endo of switch
	return False;
}    // End of function isInhibitLineEndChar

/**	メールヘッダ用にMIMEに即した文字列に変換する（内部利用メソッド／devideWithLimitingWidth関数で利用）
 *	
 *	ヘッダ文字列として利用できるように、文字列内の日本語の部分をMIMEエンコードする。
 *	文字列の中を日本語と英語に分けて、日本語の部分だけをISO-2022-JPでエンコードする。
 *
 *	@param string 処理対象の文字列
 *	@param boolean  日本語と英語の境目を改行する
 *	@return string MIMEエンコードした文字列
 */
function header_base64_encode( $str, $isSeparateLine )    {
	$strLen = mb_strlen($str);
	$encodedString = '';
	$substring = '';
	$beforeIsMBChar = False;
	$isFirstLine = True;
	for ( $i = 0 ; $i <= $strLen ; $i++ )    {
	    if ( $i == $strLen )    
	        $thisIsMBChar = ! $beforeIsMBChar;
	    else    {
	        $ch = mb_substr($str, $i , 1);
	        $thisIsMBChar = ( ord($ch) > 127 );
	    }       // Endo of else
	    if (         ( $thisIsMBChar != $beforeIsMBChar ) 
	            &&   ( $substring != '' )    )    {
	        if ( $isSeparateLine && ( ! $isFirstLine ) )
	            $encodedString .= "\t";
	        if( $thisIsMBChar )      $encodedString .= $substring;
	        else      {
	            $jisSeq = mb_convert_encoding( $substring, 'ISO-2022-JP' );
	            $jisSeq .= chr(27) . '(B';
	            $bEncoded = base64_encode( $jisSeq );
	            $encodedString .= "=?ISO-2022-JP?B?$bEncoded?=";
	        }       // Endo of else
	        if ( $isSeparateLine )    $encodedString .= "\n";
	        $substring = '';
	        $isFirstLine = False;
	    }       // Endo of if
	    $substring .= $ch;
	    $beforeIsMBChar = $thisIsMBChar;
	}       // Endo of for
	return $encodedString;
}    // End of function header_base64_encode

}    // End of class OME

?>
