{literal}
<script type="text/javascript">
var lastsearch;
var xhr;

var lastsort = {'col': 'name', 'dir': 'asc'};

function sort(col, dir) {
	lastsort[col] = col;
	lastsort[dir] = dir;
}

function search(e) {
	lastsearch = e.value;
	updateList(e.value, lastsort['col'], lastsort['dir']);
}

function updateList(search, col, dir) {
	new_rows = [];
	in_edit = [];
	delete_rows = [];
	delete_rows_color = [];

	if(xhr) {
		xhr.abort();
	}
	xhr = $.post(
		'?p=domains&pp[ajax]=1',
		{
			search: search,
			'col': col,
			'dir': dir
		},
		function(x) {
			eval("data = "+x+";");
			$('#domains_list').html(data.html);
			$("#domains_table").tablesorter();
		}
	);
}

function createDomain() {
	var name;
	name = $('#newdomain_name').val();

	$.post(
		'?a[0]=Domains-newDomain',
		{
			domain_name: name
		},
		function(x) {
			flushMsg();
			eval("var ret = " + x + ";");
			window.scroll(0,0);
			for(var c = 0; c < ret.cmds.length; c++) {
				try {
					eval(ret.cmds[c]);
				}
				catch(e) {
					console.error(e);
				}
			}
			updateList(name, 'id', 'DESC');
		}
	);

	$('#searchbox').val(name);
}

function deleteDomain(id, name) {
	if(confirm('{/literal}{t}Do you really want to delete{/t}{literal} ' + name + '? {/literal}{t}This can´t be undone!!{/t}{literal}')) {
		$.post(
			'?a[0]=Domains-deleteDomain',
			{
				domain_id: id
			},
			function(x) {
				flushMsg();
				eval("var ret = " + x + ";");
				window.scroll(0,0);
				for(var c = 0; c < ret.cmds.length; c++) {
					try {
						eval(ret.cmds[c]);
					}
					catch(e) {
						console.error(e);
					}
				}
				updateList(lastsearch, lastsort['col'], lastsort['dir']);
			}
		);
	}
}

$(document).ready(function() {
	document.getElementById('searchbox').focus();
    $("#domains_table").tablesorter();
});

</script>
{/literal}

{if $_SESSION.auth.canCreateDomain}
<div style="border: 1px #888 solid; padding: 10px; color: #555; font-size: 14px;">
	<table>
		<tr>
			<td>
				<span style="color: #555; font-size: 14px;">Domain</span>
			</td>
			<td>
				<input type="text" id="newdomain_name" />
			</td>
		</tr>
	</table>
	<a href="#" onclick="createDomain(); return false;"><img src="img/icons/add.png" /> {t}Create new domain{/t}</a>
</div>
<br />
{/if}

<div style="border: 1px #888 solid; padding: 10px; color: #555; font-size: 14px;">
	{t}Search{/t}: <input type="text" id="searchbox" onkeyup="search(this);"><br />
</div>
<br />


<script language="javascript" type="text/javascript">
	{literal}
	var domains = {
		'reloadURL' : '?p=domains&pp[ajax]=1&pp[savecallback]=1',
		'prefix' : 'domains',
		'idfield' : 'id',
		'savecallback' : '?a[0]=domains-save',
		'editfields' : [
			{
				'name': 'master',
				'type' : 'text',
				'offset' : 3
			},
			{
				'name': 'type',
				'type' : 'dropdown',
				'dropdown_type': 'record',
				'options' : ['NATIVE', 'MASTER', 'SLAVE'],
				'offset' : 4
			}
		]
	}
	{/literal}
</script>

<input type="button" value="{t}Save{/t}" onclick="saverows(domains);" class="savebutton" />
<div id="domains_list">
{include file="domains_list.tpl"}
</div>
<input type="button" value="{t}Save{/t}" onclick="saverows(domains);" class="savebutton" />
