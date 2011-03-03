{*
一覧画面テンプレート

@package jp.co.commons.forms.mail
@subpackage AdminArtile
@author 小石達也 <tkoishi@b-shock.co.jp>
*}

<h2>■ {$action.title}</h2>
<table>
	<tr>
		<th width="240">メールアドレス</th>
		<th width="120">登録日</th>
	</tr>

{foreach from=$recipients item='recipient' name='recipients'}
	<tr class="{$recipient.status}">
		<td width="240"><a href="/{$module.name}/Detail/{$recipient.id}">{$recipient.email}</a></td>
		<td width="120" align="center">
			{$recipient.create_date|date_format:'Y-m-d H:i'}
		</td>
	</tr>
{foreachelse}
	<tr>
		<td colspan="2" class="alert">登録されていません。</td>
	</tr>
{/foreach}

</table>

{* vim: set tabstop=4: *}
