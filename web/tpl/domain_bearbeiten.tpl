{literal}
<script language="javascript" type="text/javascript">
	var fields = {
		'reloadURL' : '?p=domainedit&pp[domain_id]={/literal}{$domain_id}{literal}&pp[ajax]=1',
		'prefix' : 'records',
		'idfield' : 'id',
		'savecallback' : '?a[0]=domainRecords-save',
		'editfields' : [
			{
				'name' : 'name',
				'type': 'text',
				'offset' : 1,
				'def' : '{/literal}{$template.name}{literal}'
			},
			{
				'name' : 'type',
				'type': 'dropdown',
				'dropdown_type': 'record',
				'options' : {/literal}{$record_types}{literal},
				'offset' : 2
			},
			{
				'name' : 'content',
				'type': 'text',
				'offset' : 3
			},
			{
				'name' : 'ttl',
				'type': 'text',
				'size' : 7,
				'offset' : 4,
				'def' : '{/literal}{$template.ttl}{literal}'
			},
			{
				'name' : 'prio',
				'type': 'text',
				'size' : 3,
				'offset' : 5,
				'def' : '{/literal}{$template.prio}{literal}'
			}
		],
		'extra_information' : {
			'domain_id' : {/literal}{$domain_id}{literal}
		}
	}

	$(document).ready(function()
    {
        $("#records_table").tablesorter({"textExtraction":"complex"});
        {/literal}{if $pin}{literal}
        	window.scroll(0, $('#records_tablerow_{/literal}{$pin}{literal}').offset().top);
        {/literal}{/if}{literal}
    });
</script>
{/literal}

<h2>
	{t}Editing{/t} {$domain_name}
	{if $app->Auth->isAdmin()}<a href="?p=domainuserrights&pp[domain_id]={$domain_id}" title="{t}Edit userrights{/t}"><img src="img/icons/user.png" /></a>{/if}
	<a href="?p=domaintemplate&pp[domain_id]={$domain_id}"><img src="img/icons/database_go.png" title="{t}Edit domaintemplates{/t}" /></a>
</h2>
<a href="#" onclick="addNewRow(fields); return false;" class="newEntryButton"><img src="img/icons/add.png" /> {t}New Entry{/t}</a>
<input type="button" value="{t}Save{/t}" onclick="saverows(fields);" class="savebutton" />
<div id="records_list">
{include file="domain_recordlist.tpl"}
</div>

<a href="#" onclick="addNewRow(fields); return false;" class="newEntryButton"><img src="img/icons/add.png" /> {t}New Entry{/t}</a>
<input type="button" value="{t}Save{/t}" onclick="saverows(fields);" class="savebutton" />
