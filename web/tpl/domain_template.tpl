<h2>Edit templates for records for <a href="?p=domainedit&pp[domain_id]={$domain_id}">{$domain_name}</a></h2>
<script type="text/javascript" language="javascript">
	{literal}
	var dl = {
		'reloadURL' : '?p=domaintemplate&pp[ajax]=1&pp[domain_id]={/literal}{$domain_id}{literal}',
		'prefix' : 'newrecord',
		'idfield' : 'key',
		'savecallback' : '?a[0]=domainTemplateNewRecord-save',
		'editfields' : [
			{
				'name': 'value',
				'type' : 'text',
				'offset' : 1
			},
		],
		'extra_information' : {
			'domain_id' : {/literal}{$domain_id}{literal}
		}
	}
	{/literal}
</script>


<div id="newrecord_list">
{include file="template_newrecord_domainlist.tpl"}
</div>
<input type="button" value="{t}Save{/t}" onclick="saverows(dl);" />
