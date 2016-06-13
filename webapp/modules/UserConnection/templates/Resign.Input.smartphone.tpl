{*
@package jp.co.commons.connections.mail
@subpackage UserConnection
@author 小石達也 <tkoishi@b-shock.co.jp>
*}
{include file='UserHeader'}
{include file='ErrorMessages'}
{form}
	<input type="text" name="email" value="{$params.email}" />
	<input type="submit" value="退会" />
{/form}
{include file='UserFooter'}

{* vim: set tabstop=4: *}
