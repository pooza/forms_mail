{*
一覧画面テンプレート

@package jp.co.b-shock.forms.mail
@subpackage AdminArtile
@author 小石達也 <tkoishi@b-shock.co.jp>
*}

<h2>■ {$action.title}</h2>

<form onsubmit='return false;' class="common_block">
	<input type="text" id="key" value="{$params.key}" />
	<select id="status">
		<option value="">状態...</option>
		{html_options options=$status_options selected=$params.status}
	</select>
	<input type="button" value="抽出" onclick="javascript:void(updateRecipientList())" />
	<input type="button" value="抽出の解除" onclick="javascript:void(clearRecipientCriteria())" />
</form>

<div class="alert">
	空メールによって登録された受取人のみです。
	「メンバー取得API」による受取人を含みません。
</div>

<table>
	<tr>
		<th width="360">メールアドレス</th>
		<th width="120">登録日</th>
	</tr>

{foreach from=$recipients item='recipient' name='recipients'}
	<tr class="{$recipient.status}">
		<td width="360"><a href="/{$module.name}/Detail/{$recipient.id}">{$recipient.email}</a></td>
		<td width="120" align="center">
			{$recipient.create_date|date_format:'Y-m-d(ww) H:i'}
		</td>
	</tr>
{foreachelse}
	<tr>
		<td colspan="2" class="alert">登録されていません。</td>
	</tr>
{/foreach}

	<tr>
		<td colspan="2" style="text-align:center">
{strip}
			<span><a href="javascript:void({if 1<$page}new Ajax.Updater('RecipientList','/{$module.name}/{$action.name}?page=1'){/if})"><img src="/carrotlib/images/navigation_arrow/left3.gif" width="14" height="14" alt="|&lt;" /></a></span>&nbsp;
			<span><a href="javascript:void({if 1<$page}new Ajax.Updater('RecipientList','/{$module.name}/{$action.name}?page={$page-1}'){/if})"><img src="/carrotlib/images/navigation_arrow/left1.gif" width="14" height="14" alt="&lt;" /></a></span>&nbsp;
			[{$page}]&nbsp;
			<span><a href="javascript:void({if $page<$lastpage}new Ajax.Updater('RecipientList','/{$module.name}/{$action.name}?page={$page+1}'){/if})"><img src="/carrotlib/images/navigation_arrow/right1.gif" width="14" height="14" alt="&gt;" /></a></span>&nbsp;
			<span><a href="javascript:void({if $page<$lastpage}new Ajax.Updater('RecipientList','/{$module.name}/{$action.name}?page={$lastpage}'){/if})"><img src="/carrotlib/images/navigation_arrow/right3.gif" width="14" height="14" alt="&gt;|" /></a></span>
{/strip}
		</td>
	</tr>
</table>

{* vim: set tabstop=4: *}
