{*
受取人登録画面テンプレート

@package jp.co.commons.recipients.mail
@subpackage AdminRecipient
@author 小石達也 <tkoishi@b-shock.co.jp>
*}
{include file='AdminHeader'}

<nav class="bread_crumbs">
	<a href="/AdminConnection/">接続一覧</a>
	<a href="/AdminConnection/Detail/{$connection.id}?pane=RecipientList">接続:{$connection.name}</a>
	<a href="#">{$action.title}</a>
</nav>

<h1>{$action.title}</h1>

{include file='ErrorMessages'}

{form}
	<table class="detail">
		<tr>
			<th>{$module.record_class|translate}ID</th>
			<td>{$recipient.id}</td>
		</tr>
		<tr>
			<th>メールアドレス</th>
			<td>{$recipient.email}</td>
		</tr>
		<tr>
			<th>状態</th>
			<td>
				{html_radios name='status' options=$status_options selected=$params.status}
			</td>
		</tr>
		<tr>
			<th>作成日</th>
			<td>{$recipient.create_date|date_format:'Y年 n月j日 (ww) H:i:s'}</td>
		</tr>
		<tr>
			<th>更新日</th>
			<td>{$recipient.update_date|date_format:'Y年 n月j日 (ww) H:i:s'}</td>
		</tr>
		<tr>
			<td colspan="2">
				<input type="submit" value="更新" />
			</td>
		</tr>
	</table>
{/form}

{include file='AdminFooter'}

{* vim: set tabstop=4: *}
