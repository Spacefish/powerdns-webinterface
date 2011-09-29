{literal}
<script language="javascript" type="text/javascript">
	var fields = {
		'reloadURL' : '?p=supermasters&pp[ajax]=1',
		'prefix' : 'supermasters',
		'idfield' : 'id',
		'savecallback' : '?a[0]=Supermasters-save',
		'editfields' : [
			{
				'name' : 'ip',
				'type': 'text',
				'offset' : 1
			},
			{
				'name' : 'nameserver',
				'type': 'text',
				'offset' : 2
			},
			{
				'name' : 'account',
				'type': 'text',
				'offset' : 3
			}
		]
	};

	$(document).ready(function()
    {
        $("#supermasters_table").tablesorter();
    });
</script>
{/literal}
<h2>Manage supermasters</h2>
<br />

<div id="supermasters_list">
	{include file="supermasters_list.tpl"}
</div>
<a href="#" onclick="addNewRow(fields); return false;"><img src="img/icons/add.png" /> New Entry</a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="Save" onclick="saverows(fields);" />
