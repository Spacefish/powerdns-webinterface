<table class="list" id="supermasters_table">
	<thead>
		<tr>
			<th>Id</th>
			<th>IP</th>
			<th>Nameserver</th>
			<th>Account</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
		{foreach from=$supermasters item="i"}
		<tr style="background-color: #{cycle values="eee,c8c8c8"};" onclick="editrow(fields, {$i.id});" id="supermasters_tablerow_{$i.id}">
			<td><span id="supermasters_id">{$i.id}</span></td>
			<td><span id="supermasters_ip_{$i.id}">{$i.ip}</span></td>
			<td><span id="supermasters_nameserver_{$i.id}">{$i.nameserver}</span></td>
			<td><span id="supermasters_account_{$i.id}">{$i.account}</span></td>
			<td>
				<a href="#" onclick="deleterow(fields, {$i.id}); return false;"><img src="img/icons/delete.png" /></a>
			</td>
		</tr>
		{foreachelse}
		<tr>
			<td colspan="9">No supermasters found</td>
		</tr>
		{/foreach}
	</tbody>
</table>
