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
	<table class="detail">
		<tr>
			<th>{$module.record_class|translate}ID</th>
			<td>{$article.id}</td>
		</tr>
		<tr>
			<th>タイトル</th>
			<td>
				<input type="text" name="title" value="{$params.title}" size="48" maxlength="128" />
			</td>
		</tr>
		<tr>
			<th>本文</th>
			<td>
				<textarea id="body" name="body" cols="72" rows="20" />{$params.body}</textarea>
				<div class="tag_cloud">
					{foreach from=$fields item='field'}
						<a href="javascript:void(FormsMailLib.putTemplateField($('body'), '{$field.name}'))">{$field.label}</a>
					{/foreach}
				</div>
			</td>
		</tr>
		<tr>
			<th>ケータイ向け本文</th>
			<td>
				<textarea id="body_mobile" name="body_mobile" cols="35" rows="20" />{$params.body_mobile}</textarea>
				<div class="tag_cloud">
					{foreach from=$fields item='field'}
						<a href="javascript:void(FormsMailLib.putTemplateField($('body_mobile'), '{$field.name}'))">{$field.label}</a>
					{/foreach}
				</div>
			</td>
		</tr>
		<tr>
			<th>セグメント</th>
			<td id="criteria_container">
				{foreach from=$fields item='field'}
					{if $field.choices}
						{assign var='field_name' value='fields['|cat:$field.name|cat:']'}
						<div class="common_block" id="criteria_{$field.name}">
							<div class="heading">
								<label><input type="checkbox" name="selected_fields[{$field.name}]" onchange="FormsMailLib.setCriteriaActivity('{$field.name}', this.checked)" {if $params.selected_fields[$field.name]}checked="checked"{/if} />{$field.label}</label>
							</div>
							<div class="choices clearfix">
								{html_checkboxes name=$field_name values=$field.choices output=$field.choices selected=$params.fields[$field.name]}
							</div>
							<div class="controller">
								<input type="button" id="criteria_{$field.name}_checkall" onclick="FormsMailLib.setCriteriaStatus('{$field.name}', true)" value="全選択" class="button" />
								<input type="button" id="criteria_{$field.name}_uncheckall" onclick="FormsMailLib.setCriteriaStatus('{$field.name}', false)" value="全解除" class="button" />
							</div>
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
			<th>作成日</th>
			<td>{$article.create_date|date_format:'Y年 n月j日 (ww) H:i:s'}</td>
		</tr>
		<tr>
			<th>更新日</th>
			<td>{$article.update_date|date_format:'Y年 n月j日 (ww) H:i:s'}</td>
		</tr>
		<tr>
			<td colspan="2">
				<input type="submit" value="更新" {if $article.is_published}disabled="disabled"{/if} />
				<input type="button" value="この{$module.record_class|translate}を削除..." onclick="CarrotLib.confirmDelete('{$module.name}','Delete','{$module.record_class|translate}')" {if $article.is_published}disabled="disabled"{/if}/>
			</td>
		</tr>
	</table>
{/form}

<script type="text/javascript">
document.observe('dom:loaded', function(){ldelim}
{literal}
  new InputCalendar('publish_date', {
    lang:'ja',
    format:'yyyy-mm-dd HH:MM',
    enableHourMinute:true
  });
{/literal}

{foreach from=$fields item='field'}
  {if $field.choices}
  FormsMailLib.setCriteriaActivity(
    '{$field.name}',
    {if $params.selected_fields[$field.name]}true{else}false{/if}
  );
  {/if}
{/foreach}
{rdelim});
</script>

{include file='AdminFooter'}

{* vim: set tabstop=4: *}
