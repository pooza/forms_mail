{*
記事メール

@package jo.co.commons.forms.mail
@author 小石達也 <tkoishi@b-shock.co.jp>
*}
From: {$connection.sender_email}
To: {$recipient.email}
Subject: {$article.title}

{include file=$article.body_template.path}


今後、こうしたメールが不要でしたら、こちらまで。
{$recipient.resign_url}

=====
{const name='app_name_ja'}
email: {$connection.sender_email}