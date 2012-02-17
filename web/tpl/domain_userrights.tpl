{literal}
<script language="javascript" type="text/javascript">
	var fields = {
		'reloadURL' : '?p=domainuserrights&pp[domain_id]={/literal}{$domain_id}{literal}&pp[ajax]=1',
		'prefix' : 'ur',
		'idfield' : 'id',
		'savecallback' : '?a[0]=DomainUserrights-save',
		'editfields' : [
			{ 
				'name' : 'haspower',
				'type' : 'checkbox',
				'offset' : 2
			}
		],
		'extra_information' : {
			'domain_id' : {/literal}{$domain_id}{literal}
		}
	}
	
	$(document).ready(function() 
    { 
        $("#records_table").tablesorter();
    });
</script>
{/literal}

<h2>Edit rights for <a href="?p=domainedit&pp[domain_id]={$domain_id}">{$domain_name}</a></h2>

<input type="button" value="Save" onclick="saverows(fields);" class="savebutton" />
<div id="ur_list">
{include file="domain_userrights_list.tpl"}
</div>
<input type="button" value="Save" onclick="saverows(fields);" class="savebutton" />
