<table class="list" id="newrecord_table">
	<tr>
		<th>Key</th>
		<th>Value</th>
	</tr>
	{foreach from=$template_newrecord  item="i"}
	<tr style="background-color: #{cycle values="eee,c8c8c8"};" onclick="editrow(dl, '{$i.key}');" id="newrecord_tablerow_{$i.key}">
		<td>{$i.key}</td>
		<td><span id="newrecord_value_{$i.key}">{$i.value}</span></td>
	</tr>
	{/foreach}
</table>