{*
記事登録画面テンプレート

@package jp.co.commons.articles.mail
@subpackage AdminArticle
@author 小石達也 <tkoishi@b-shock.co.jp>
*}
{include file='AdminHeader'}

<div id="BreadCrumbs">
	<a href="/AdminConnection/">接続一覧</a>
	<a href="/AdminConnection/Detail/{$connection.id}?pane=ArticleList">接続:{$connection.name}</a>
	<a href="#">{$action.title}</a>
</div>

<h1>{$action.title}</h1>

{include file='ErrorMessages'}

{form}
	<table class="Detail">
		<tr>
			<th>タイトル</th>
			<td>
				<input type="text" name="title" value="{$params.title}" size="48" maxlength="128" />
			</td>
		</tr>
		<tr>
			<th>本文</th>
			<td>
				<textarea name="body" cols="72" rows="8" />{$params.body}</textarea>
			</td>
		</tr>
		<tr>
			<th>抽出</th>
			<td>
				{foreach from=$fields item='field'}
					{if $field.choices}
						{assign var='field_name' value='fields['|cat:$field.name|cat:']'}
						<div class="common_block">
							{$field.label}:<br/>
							{html_checkboxes name=$field_name values=$field.choices output=$field.choices selected=$params.fields[$field.name]}
						</div>
					{/if}
				{/foreach}
			</td>
		</tr>
		<tr>
			<th>発行日</th>
			<td>
				<input type="text" id="publish_date" name="publish_date" value="{$params.publish_date|date_format:'Y-m-d H:i'}" size="18" maxlength="18" class="english" />
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<input type="submit" value="登録" />
			</td>
		</tr>
	</table>
{/form}

<script type="text/javascript">
document.observe('dom:loaded', function(){ldelim}
  new InputCalendar('publish_date', {ldelim}
    lang:'ja',
    format:'yyyy-mm-dd HH:MM',
    enableHourMinute:true
  {rdelim});
{rdelim});
</script>

{include file='AdminFooter'}

{* vim: set tabstop=4: *}
