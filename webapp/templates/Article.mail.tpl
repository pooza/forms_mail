{*
記事メール

@package jp.co.b-shock.forms.mail
@author 小石達也 <tkoishi@b-shock.co.jp>
*}
From: {$connection.sender_email}
To: {$recipient.email}
Subject: {$article.title}
{if $connection.replyto_email}
Reply-To: {$connection.replyto_email}
{/if}

{$article.body}


今後、こうしたメールが不要でしたら、こちらまで。
{$connection.resign_url}

=====
メールマガジン
email: info@dipps.co.jp