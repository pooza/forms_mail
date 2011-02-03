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
				<th>URL</th>
				<td>
					<input type="text" name="url" value="{$params.url}" size="48" maxlength="64" class="english"/>
				</td>
			</tr>
			<tr>
				<th>ユーザー名</th>
				<td>
					<input type="text" name="uid" value="{$params.uid}" size="24" maxlength="32" class="english"/>
				</td>
			</tr>
			<tr>
				<th>パスワード</th>
				<td>
					<input type="password" name="password" value="{$params.password}" size="24" maxlength="32" class="english"/>
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
