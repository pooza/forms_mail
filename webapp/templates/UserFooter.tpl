{*
ユーザー画面 テンプレートひな形

@package jo.co.commons.forms.mail
@author 小石達也 <tkoishi@b-shock.co.jp>
@version $Id$
*}
<div id="Footer">
{const name='app_name_en'} {const name='app_ver'}
(Powered by {if 'package_name'|translate}{const name='package_name'} {const name='package_ver'} / {/if}
{const name='carrot_name'} {const name='carrot_ver'})
</div>
</div>
</body>
</html>

{* vim: set tabstop=4: *}