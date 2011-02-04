{*
一覧画面テンプレート

@package jp.co.commons.forms.mail
@subpackage AdminArtile
@author 小石達也 <tkoishi@b-shock.co.jp>
*}

<h2>■ {$action.title}</h2>
<table>
	<tr>
		<th width="240">タイトル</th>
		<th width="120">発行日</th>
		<th width="60">発行済</th>
	</tr>
	<tr>
		<td colspan="3">
			<a href="/{$module.name}/Create">新しい{$module.record_class|translate}を登録...</a>
		</td>
	</tr>

{foreach from=$articles item='article' name='articles'}
	<tr class="{if $article.is_published}hide{/if}">
		<td width="240"><a href="/{$module.name}/Detail/{$article.id}">{$article.title}</a></td>
		<td width="120" align="center">
			{if $article.publish_date}{$article.publish_date|date_format:'Y-m-d H:i'}{/if}
		</td>
		<td width="60" align="center">
			{if $article.is_published}○{/if}
		</td>
	</tr>
{foreachelse}
	<tr>
		<td colspan="3" class="alert">登録されていません。</td>
	</tr>
{/foreach}

</table>

{* vim: set tabstop=4: *}
