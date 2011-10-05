{literal}
<script language="javascript" type="text/javascript">
	var fields = {
		'reloadURL' : '?p=user&pp[ajax]=1',
		'prefix' : 'users',
		'idfield' : 'id',
		'savecallback' : '?a[0]=Users-save',
		'editfields' : [
			{
				'name' : 'username',
				'type': 'text',
				'offset' : 1
			},
			{
				'name' : 'isadmin',
				'type': 'checkbox',
				'offset' : 2
			},
			{
				'name' : 'cancreatedomain',
				'type': 'checkbox',
				'offset' : 3
			}
		]
	};

	function createUser() {
		$.post(
			'?a[0]=Users-newuser',
			{
				'username' : $('#username').val(),
				'password' : $('#password').val(),
				'isadmin' : $('#isadmin').attr('checked') ? 1: 0

			},
			ajaxReplyHandler
		);
	}

	function changeUserPw(userid, username) {
		var password;
		password = prompt("Please enter the new password for " + username);

		if(password) {
			$.post(
				"?a[0]=Users-changePw",
				{
					'userid': userid,
					'username': username,
					'password': password
				},
				ajaxReplyHandler
			);
		}
	}

	$(document).ready(function()
    {
        $("#records_table").tablesorter();
    });
</script>
{/literal}
<div style="border: 1px #888 solid; padding: 10px; color: #555; font-size: 14px;">
	<table>
		<tr>
			<td>
				<span style="color: #555; font-size: 14px;">{t}Username{/t}</span>
			</td>
			<td>
				<input type="text" id="username" />
			</td>
		</tr>
		<tr>
			<td>
				<span style="color: #555; font-size: 14px;">{t}Password{/t}</span>
			</td>
			<td>
				<input type="text" id="password" />
			</td>
		</tr>
		<tr>
			<td>
				<span style="color: #555; font-size: 14px;">{t}Admin{/t}</span>
			</td>
			<td>
				<input type="checkbox" id="isadmin" />
			</td>
		</tr>
	</table>
	<a href="#" onclick="createUser(); return false;"><img src="img/icons/add.png" /> {t}Create new user{/t}</a>
</div>
<br />
<div id="users_list">
	{include file="user_list.tpl"}
</div>
<input type="button" value="{t}Save{/t}" onclick="saverows(fields);" />
<br />
<span style="color: #999; font-size: 12px;">{t}The user has to relogin for changes to take effect!{/t}</span>
