{*
接続詳細画面テンプレート

@package jp.co.commons.connections
@subpackage AdminConnection
@author 小石達也 <tkoishi@b-shock.co.jp>
@version $Id$
*}
{include file='AdminHeader'}

<div id="BreadCrumbs">
	<a href="/{$module.name}/">{$module.record_class|translate}一覧</a>
	<a href="#">{$action.title}</a>
</div>

<h1>{$action.title}</h1>

<div class="tabs10">
	<ul id="Tabs">
		<li><a href="#DetailForm"><span>{$module.record_class|translate}詳細</span></a></li>
		<li><a href="#FieldList"><span>フィールド</span></a></li>
		<li><a href="#ArticleList"><span>記事</span></a></li>
		<li><a href="#RecipientList"><span>受取人</span></a></li>
	</ul>
</div>

<div id="DetailForm" class="panel">
	{form}
		<h2>■{$module.record_class|translate}詳細</h2>

		{include file='ErrorMessages'}

		<table class="Detail">
			<tr>
				<th>{$module.record_class|translate}ID</th>
				<td>{$connection.id}</td>
			</tr>
			<tr>
				<th>名前</th>
				<td>
					<input type="text" name="name" value="{$params.name}" size="24" maxlength="64" />
				</td>
			</tr>
			<tr>
				<th>フィールド取得APIのURL</th>
				<td>
					<input type="text" name="fields_url" value="{$params.fields_url}" size="64" maxlength="128" class="english"/>
				</td>
			</tr>
			<tr>
				<th>メンバー取得APIのURL</th>
				<td>
					<input type="text" name="members_url" value="{$params.members_url}" size="64" maxlength="128" class="english"/>
				</td>
			</tr>
			<tr>
				<th>送信時メールアドレス</th>
				<td>
					<input type="text" name="sender_email" value="{$params.sender_email}" size="32" maxlength="64" class="english"/>
				</td>
			</tr>
			<tr>
				<th>空メール受信用<br/>メールアドレス</th>
				<td>
					<input type="text" name="emptymail_receive_email" value="{$params.emptymail_receive_email}" size="32" maxlength="64" class="english"/>
				</td>
			</tr>
			<tr>
				<th>空メール受信時の<br/>返信文面</th>
				<td>
					<textarea name="emptymail_reply_body" cols="60" rows="8">{$params.emptymail_reply_body}</textarea>
				</td>
			</tr>
			<tr>
				<th>作成日</th>
				<td>{$connection.create_date|date_format:'Y年 n月j日 (ww) H:i:s'}</td>
			</tr>
			<tr>
				<th>更新日</th>
				<td>{$connection.update_date|date_format:'Y年 n月j日 (ww) H:i:s'}</td>
			</tr>
			<tr>
				<td colspan="2">
					<input type="submit" value="更新" />
					<input type="button" value="この{$module.record_class|translate}を削除..." onclick="CarrotLib.confirmDelete('{$module.name}','Delete','{$module.record_class|translate}')" />
				</td>
			</tr>
		</table>
	{/form}
</div>

<div id="FieldList" class="panel">
<h2>■ フィールド一覧</h2>
<table>
	<tr>
		<th width="120">名前</th>
		<th width="120">ラベル</th>
		<th width="300">選択肢</th>
	</tr>

{foreach from=$fields item='field' name='fields'}
	<tr>
		<td width="120"><code>{$field.name}</code></td>
		<td width="120">{$field.label}</td>
		<td width="300">
			{foreach from=$field.choices item='choice'}
				<span class="choice">{$choice}</span>
			{/foreach}
		</td>
	</tr>
{foreachelse}
	<tr>
		<td colspan="3" class="alert">該当する項目がありません。</td>
	</tr>
{/foreach}

</table>
</div>

<div id="ArticleList" class="panel"></div>
<div id="RecipientList" class="panel"></div>

<script type="text/javascript">
document.observe('dom:loaded', function(){ldelim}
  new ProtoTabs('Tabs', {ldelim}
    defaultPanel:'{$params.pane|default:'DetailForm'}',
    ajaxUrls: {ldelim}
      ArticleList: '/AdminArticle/List',
      RecipientList: '/AdminRecipient/List'
    {rdelim}
  {rdelim});
{rdelim});
</script>

{include file='AdminFooter'}

{* vim: set tabstop=4: *}
