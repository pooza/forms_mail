{*
登録時メール

@package jo.co.commons.forms.mail
@author 小石達也 <tkoishi@b-shock.co.jp>
*}
From: {$connection.sender_email}
To: {$recipient.email}
Subject: ご登録頂き、ありがとうございました。

{$connection.emptymail_reply_body}

=====
{const name='app_name_ja'}
email: {$connection.sender_email}
