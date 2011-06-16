{*
登録時メール

@package jp.co.b-shock.forms.mail
@author 小石達也 <tkoishi@b-shock.co.jp>
*}
From: {$connection.sender_email}
To: {$recipient.email}
Subject: ご登録頂き、ありがとうございました。
{if $connection.replyto_email}
Reply-To: {$connection.replyto_email}
{/if}

{$connection.emptymail_reply_body}

=====
{const name='app_name_ja'}
email: {$connection.sender_email}
