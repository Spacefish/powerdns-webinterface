<table class="list" id="domains_list" width="500">
	<thead>
		<tr>
			<th>Userid</th>
			<th>User</th>
			<th>Access</th>
		</tr>
	</thead>
	<tbody>
		{foreach from=$ur item="i"}
		<tr style="background-color: #{cycle values="eee,c8c8c8"};" onclick="editrow(fields, {$i.id});">
			<td>{$i.id}</td>
			<td><span id="ur_username_{$i.id}">{$i.username}</span></td>
			<td><span id="ur_haspower_{$i.id}">{if $i.haspower}[X]{else}[ ]{/if}</span></td>
		</tr>
		{foreachelse}
		<tr>
			<td colspan="3">No user found..</td>
		</tr>
		{/foreach}
	</tbody>
</table>