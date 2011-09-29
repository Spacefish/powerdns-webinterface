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
	updateList(e.value, lastsort['col'], lastsort['dir']);
}

function updateList(search, col, dir) {
	if(xhr) {
		xhr.abort();
	}
	xhr = $.post(
		'?p=records&pp[ajax]=1',
		{
			search: search,
			'col': col,
			'dir': dir
		},
		function(x) {
			if(!x) { return; }
			eval("data = "+x+";");
			$('#dlist').html(data.html);
			$('#records_list').tablesorter({"textExtraction":"complex"});
		}
	);
}

$(document).ready(function() {
	document.getElementById('searchbox').focus();
	$('#records_list').tablesorter({"textExtraction":"complex"});
	if($('#searchbox').val()) {
		search({'value':$('#searchbox').val()});
	}
});

</script>
{/literal}

<h2>Fulltext search in ALL records</h2>

<div style="border: 1px #888 solid; padding: 10px; color: #555; font-size: 14px;">
	Search: <input type="text" id="searchbox" onkeyup="search(this);"><br />
</div>
<br />
<div id="dlist">
{include file="records_list.tpl"}
</div>
