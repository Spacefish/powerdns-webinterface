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
				<span style="color: #555; font-size: 14px;">Username</span>
			</td>
			<td>
				<input type="text" id="username" />
			</td>
		</tr>
		<tr>
			<td>
				<span style="color: #555; font-size: 14px;">Password</span>
			</td>
			<td>
				<input type="text" id="password" />
			</td>
		</tr>
		<tr>
			<td>
				<span style="color: #555; font-size: 14px;">Admin</span>
			</td>
			<td>
				<input type="checkbox" id="isadmin" />
			</td>
		</tr>
	</table>
	<a href="#" onclick="createUser(); return false;"><img src="img/icons/add.png" /> Create new user</a>
</div>
<br />
<div id="users_list">
	{include file="user_list.tpl"}
</div>
<input type="button" value="Save" onclick="saverows(fields);" />
