{*
汎用テンプレート
 
@package org.carrot-framework
@subpackage Console
@author 小石達也 <tkoishi@b-shock.co.jp>
@version $Id: Crypt.Error.tpl 2160 2010-06-19 14:54:59Z pooza $
*}
{include file='UserHeader'}

{foreach from=$errors key=code item=message}
{$code}/{$code|translate:'carrot.Console'}:  {$message}
{/foreach}

{include file='UserFooter'}
{* vim: set tabstop=4: *}
