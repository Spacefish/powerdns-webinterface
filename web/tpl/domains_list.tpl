<table class="list" id="domains_table">
	<thead>
		<tr>
			<th>Id</th>
			<th>Domain</th>
			<th>Master</th>
			<th>Type</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
		{foreach from=$domains item="domain"}
		<tr style="background-color: #{cycle values="eee,c8c8c8"};">
			<td><span id="domains_id_{$domain.id}">{$domain.id}</span></td>
			<td><a href="?p=domainedit&pp[domain_id]={$domain.id}">{$domain.name}</a></td>
			<td onclick="editrow(domains, {$domain.id});"><span id="domains_master_{$domain.id}">{$domain.master}</span></td>
			<td onclick="editrow(domains, {$domain.id});"><span id="domains_type_{$domain.id}">{$domain.type}</span></td>
			<td>
				<a href="?p=domainedit&pp[domain_id]={$domain.id}"><img src="img/icons/pencil.png" /></a>
				{if $app->Auth->isAdmin()}<a href="?p=domainuserrights&pp[domain_id]={$domain.id}"><img src="img/icons/user.png" /></a>{/if}
				<a href="?p=domaintemplate&pp[domain_id]={$domain.id}"><img src="img/icons/database_go.png" /></a>
				<a href="#" onclick="deleteDomain({$domain.id}, '{if $domain.name_clean}{$domain.name_clean}{else}{$domain.name}{/if}');"><img src="img/icons/delete.png" /></a>
			</td>
		</tr>
		{foreachelse}
		<tr>
			<td colspan="3">Nothing found</td>
		</tr>
		{/foreach}
	</tbody>
</table>
