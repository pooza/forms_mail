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
	<table class="detail">
		<tr>
			<th>名前</th>
			<td>
				<input type="text" name="name" value="{$params.name}" size="24" maxlength="64" />
			</td>
		</tr>
		<tr>
			<th>送信時メールアドレス</th>
			<td>
				<input type="text" name="sender_email" value="{$params.sender_email}" size="32" maxlength="64" class="english"/>
			</td>
		</tr>
		<tr>
			<th>返信先メールアドレス</th>
			<td>
				<input type="text" name="replyto_email" value="{$params.replyto_email}" size="32" maxlength="64" class="english"/><br/>
				<span class="alert">配信記事に「返信」した場合の宛先です。バウンスメールの自動処理を行う場合に指定してください。</span>
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
			<th>空メール受信用<br/>メールアドレス</th>
			<td>
				<input type="text" name="emptymail_email" value="{$params.emptymail_email}" size="32" maxlength="64" class="english"/>
			</td>
		</tr>
		<tr>
			<th>空メール受信時の<br/>返信文面</th>
			<td>
				<div>
					<textarea name="emptymail_reply_body" cols="72" rows="8">{$params.emptymail_reply_body}</textarea>
				</div>
				<div class="alert">空欄の場合は、返信を行いません。</div>
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
