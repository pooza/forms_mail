{*
エラーメッセージ表示 テンプレート

@package org.carrot-framework
@author 小石達也 <tkoishi@b-shock.co.jp>
@version $Id: ErrorMessages.Console.tpl 2160 2010-06-19 14:54:59Z pooza $
*}
{if $errors}
{foreach from=$errors key=code item=message}
{if !$hide_error_code}{$code|translate:$error_code_dictionary}:{/if} {$message}
{/foreach}

{/if}
{* vim: set tabstop=4: *}