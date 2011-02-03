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
			<th>BASIC認証のユーザー名</th>
			<td>
				<input type="text" name="basicauth_uid" value="{$params.basicauth_uid}" size="24" maxlength="32" class="english"/>
			</td>
		</tr>
		<tr>
			<th>BASIC認証のパスワード</th>
			<td>
				<input type="password" name="basicauth_password" value="{$params.basicauth_password}" size="24" maxlength="32" class="english"/>
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
