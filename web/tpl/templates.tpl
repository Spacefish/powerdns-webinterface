<script language="javascript" type="text/javascript">

</script>
<h2>{t}New Record in Domain{/t}</h2>
<script language="javascript" type="text/javascript">
{literal}
	var dl = {
		'reloadURL' : '?p=templates&pp[ajax]=1&pp[type]=newrecord',
		'prefix' : 'newrecord',
		'idfield' : 'id',
		'savecallback' : '?a[0]=templateNewRecord-save',
		'editfields' : [
			{
				'name': 'value',
				'type' : 'text',
				'offset' : 1
			}
		]
	}
{/literal}
</script>
<div id="newrecord_list">
	{include file="template_newrecordlist.tpl"}
</div>
<input type="button" value="{t}Save{/t}" onclick="saverows(dl);" />

<script language="javascript" type="text/javascript">
{literal}
	var fields = {
		'reloadURL' : '?p=templates&pp[ajax]=1&pp[type]=newdomain',
		'prefix' : 'records',
		'idfield' : 'id',
		'savecallback' : '?a[0]=templateNewDomain-save',
		'editfields' : [
			{ 
				'name' : 'name',
				'type': 'text',
				'offset' : 1
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
				'offset' : 4
			},
			{
				'name' : 'prio',
				'type': 'text',
				'size' : 3,
				'offset' : 5
			}
		],
		'extra_information' : {
			
		}
	}
{/literal}
</script>


<h2>{t}Default Records for new Domains{/t}</h2>
<span style="color: #999; font-size: 12px;">
	{t}You can use [DOMAIN] (mydomain.tld) and [STAMP] (2010061501) as a placeholder.{/t}
</span>
<br /><br />
<a href="#" onclick="addNewRow(fields); return false;"><img src="img/icons/add.png" /> {t}New Entry{/t}</a>
<div id="records_list">
{include file="template_records_newdomainlist.tpl"}
</div>
<input type="button" value="{t}Save{/t}" onclick="saverows(fields);" />
