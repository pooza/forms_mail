{*
接続登録画面テンプレート

@package jp.co.commons.connections.mail
@subpackage AdminConnection
@author 小石達也 <tkoishi@b-shock.co.jp>
*}
{include file='AdminHeader'}

<div id="BreadCrumbs">
	<a href="/{$module.name}/">接続一覧</a>
	<a href="#">{$action.title}</a>
</div>

<h1>{$action.title}</h1>

{include file='ErrorMessages'}

{form}
	<table class="Detail">
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
			<td colspan="2">
				<input type="submit" value="登録" />
			</td>
		</tr>
	</table>
{/form}

{include file='AdminFooter'}

{* vim: set tabstop=4: *}
