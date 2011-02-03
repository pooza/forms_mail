{*
一覧画面テンプレート

@package jp.co.commons.forms.mail
@subpackage AdminConnection
@author 小石達也 <tkoishi@b-shock.co.jp>
*}
{include file='AdminHeader'}

<div id="BreadCrumbs">
	<a href="#">{$action.title}</a>
</div>

{include file='ErrorMessages'}

<h1>{$action.title}</h1>
<table>
	<tr>
		<th width="120">名前</th>
		<th width="420">APIのURL</th>
		<th width="60"></th>
	</tr>
	<tr>
		<td colspan="3">
			<a href="/{$module.name}/Create">新しい{$module.record_class|translate}を登録...</a>
		</td>
	</tr>

{foreach from=$connections item='connection' name='connections'}
	<tr class="{$connection.status}">
		<td width="120"><a href="/{$module.name}/Detail/{$connection.id}">{$connection.name}</a></td>
		<td width="420">
			<code>{$connection.fields_url|truncate:64}</code><br/>
			<code>{$connection.members_url|truncate:64}</code>
		</td>
		<td width="60" align="center">
		{if $smarty.foreach.connections.first}
			<img src="/carrotlib/images/navigation_arrow/top_off.gif" width="11" height="11" alt="TOP"/>
			<img src="/carrotlib/images/navigation_arrow/up_off.gif" width="11" height="11" alt="UP"/>
		{else}
			<a href="/{$module.name}/SetRank/{$connection.id}?option=top"><img src="/carrotlib/images/navigation_arrow/top_on.gif" width="11" height="11" alt="TOP"/></a>
			<a href="/{$module.name}/SetRank/{$connection.id}?option=up"><img src="/carrotlib/images/navigation_arrow/up_on.gif" width="11" height="11" alt="UP"/></a>
		{/if}

		{if $smarty.foreach.connections.last}
			<img src="/carrotlib/images/navigation_arrow/down_off.gif" width="11" height="11" alt="DOWN"/>
			<img src="/carrotlib/images/navigation_arrow/bottom_off.gif" width="11" height="11" alt="DOWN"/>
		{else}
			<a href="/{$module.name}/SetRank/{$connection.id}?option=down"><img src="/carrotlib/images/navigation_arrow/down_on.gif" width="11" height="11" alt="DOWN"/></a>
			<a href="/{$module.name}/SetRank/{$connection.id}?option=bottom"><img src="/carrotlib/images/navigation_arrow/bottom_on.gif" width="11" height="11" alt="BOTTOM"/></a>
		{/if}
		</td>
	</tr>
{foreachelse}
	<tr>
		<td colspan="3" class="alert">登録されていません。</td>
	</tr>
{/foreach}

</table>

{include file='AdminFooter'}

{* vim: set tabstop=4: *}
