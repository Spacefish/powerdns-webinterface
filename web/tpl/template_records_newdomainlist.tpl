<table class="list" id="records_table">
	<tr>
		<th>Id</th>
		<th>{t}Name{/t}</th>
		<th>{t}Type{/t}</th>
		<th>{t}Content{/t}</th>
		<th>TTL</th>
		<th>{t}Priority{/t}</th>
		<th>{t}Action{/t}</th>
	</tr>
	{foreach from=$template_records_newdomain  item="i"}
	<tr style="background-color: #{cycle values="eee,c8c8c8"};" onclick="editrow(fields, {$i.id});" id="records_tablerow_{$i.id}">
		<td>{$i.id}</td>
		<td><span id="records_name_{$i.id}">{$i.name}</span></td>
		<td><span id="records_type_{$i.id}">{$i.type}</span></td>
		<td><span id="records_content_{$i.id}">{$i.content}</span></td>
		<td><span id="records_ttl_{$i.id}">{$i.ttl}</span></td>
		<td><span id="records_prio_{$i.id}">{$i.prio}</span></td>
		<td>
			<a href="#" onclick="deleterow(fields, {$i.id}); return false;"><img src="img/icons/delete.png" /></a>
		</td>
	</tr>
	{/foreach}
</table>
