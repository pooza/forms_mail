{*
エラー文面テンプレート
 
@package org.carrot-framework
@author 小石達也 <tkoishi@b-shock.co.jp>
@version $Id: BSException.mail.tpl 2160 2010-06-19 14:54:59Z pooza $
*}
Subject: [{const name='app_name_ja'}] {$priority}

{$priority}:
{$message}


クライアントホスト:
{$client_host.address}
{$client_host.name|default:'(名前解決に失敗)'}

ブラウザ:
{$useragent.type}系
{$useragent.name}
