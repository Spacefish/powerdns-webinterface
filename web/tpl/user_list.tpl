<table class="list" id="user_list">
	<thead>
		<tr>
			<th>Id</th>
			<th>Username</th>
			<th>Admin</th>
			<th>Can create new Domains</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
		{foreach from=$users item="user"}
		<tr style="background-color: #{cycle values="eee,c8c8c8"};" onclick="editrow(fields, {$user.id});" id="users_tablerow_{$user.id}">
			<td>{$user.id}</td>
			<td><span id="users_username_{$user.id}">{$user.username}</span></td>
			<td><span id="users_isadmin_{$user.id}">{if $user.isAdmin}[X]{else}[ ]{/if}</span></td>
			<td><span id="users_cancreatedomain_{$user.id}">{if $user.canCreateDomain}[X]{else}[ ]{/if}</span></td>
			<td>
				<a href="#" onclick="changeUserPw({$user.id}, '{$user.username}'); return false;"><img src="img/icons/key.png" /></a>
				<a href="#" onclick="deleterow(fields, {$user.id}); return false;"><img src="img/icons/delete.png" /></a>
			</td>
		</tr>
		{foreachelse}
		<tr>
			<td colspan="9">No users found</td>
		</tr>
		{/foreach}
	</tbody>
</table>
