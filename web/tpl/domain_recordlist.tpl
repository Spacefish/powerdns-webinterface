<table class="list" id="records_table">
	<thead>
		<tr>
			<th>Id</th>
			<th class="{literal}{sorter: 'natural'}{/literal}">Name</th>
			<th>{t}Type{/t}</th>
			<th class="{literal}{sorter: 'natural'}{/literal}">{t}Content{/t}</th>
			<th>TTL</th>
			<th>{t}Priority{/t}</th>
			<th>{t}Last change{/t}</th>
			<th>{t}Action{/t}</th>
		</tr>
	</thead>
	<tbody>
	{foreach from=$records item="record"}
		<tr style="background-color: #{if $record.id == $pin}aaf{else}{cycle values="eee,c8c8c8"}{/if};" id="records_tablerow_{$record.id}">
			<td>{$record.id}</td>
			<td onclick="editrow(fields, {$record.id});"><span id="records_name_{$record.id}">{$record.name}</span></td>
			<td onclick="editrow(fields, {$record.id});"><span id="records_type_{$record.id}">{$record.type}</span></td>
			<td onclick="editrow(fields, {$record.id});"><div style="overflow: hidden; max-width: 500px;"><span id="records_content_{$record.id}">{$record.content}</span></div></td>
			<td onclick="editrow(fields, {$record.id});"><span id="records_ttl_{$record.id}">{$record.ttl}</span></td>
			<td onclick="editrow(fields, {$record.id});"><span id="records_prio_{$record.id}">{$record.prio}</span></td>
			<td><span id="records_change-date_{$record.id}">{$record.change_date|date_format:"%c"}</span></td>
			<td>
				{* <a href="#" onclick="editrow(fields, {$record.id});"><img src="/img/icons/pencil.png" /></a> *}
				<a href="#" onclick="deleterow(fields, {$record.id}); return false;"><img src="img/icons/delete.png" /></a>
			</td>
		</tr>
	{/foreach}
	</tbody>
</table>
