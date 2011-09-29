// extend Array to support in_array function
Array.prototype.in_array = function(needle) {
	for(var i=0; i < this.length; i++) {
		if(this[ i] === needle) {
			return true;
		}
	}
	return false;
}

// global variables to store state of fields
var in_edit = [];
var new_rows = [];
var delete_rows = [];
var delete_rows_color = [];


function saverows(fields) {
	var rowid;
	var field_id;
	var value;
	var data = [];
	var data_new = [];
	
	// edited
	for(var i = 0; i < in_edit.length; i++) {
		rowid = in_edit[i];
		var tmp = new Object();
		
		tmp['id'] = rowid;
		for(var j = 0; j < fields.editfields.length; j++) {
			field_id = fields.prefix + '_' + fields.editfields[j].name + '_' + rowid + '_editor';
			
			switch($('#' + field_id).attr("type")) {
				case "checkbox":
					value = $('#' + field_id).attr("checked") ? 1 : 0;
					break;
				default:
					value = $('#' + field_id).val();
					break;
			}
			
			
			tmp[fields.editfields[j].name] = value;
			
			console.log("ROW EDITED: Rowid: " + rowid + " Field: " + fields.editfields[j].name + " Value: " + value);
		}
		data.push(tmp);
	}
	
	// new
	for(var i = 0; i < new_rows.length; i++) {
		rowid = new_rows[i];
		var tmp = new Object();
		
		for(var j = 0; j < fields.editfields.length; j++) {
			field_id = fields.prefix + '_' + fields.editfields[j].name + '_NEW' +  rowid + '_editor';
			
			value = $('#' + field_id).val();
			
			tmp[fields.editfields[j].name] = value;
			
			console.log("ROW NEW: Rowid: " + rowid + " Field: " + fields.editfields[j].name + " Value: " + value);
		}
		data_new.push(tmp);
	}
	
	$.post(
		fields.savecallback,
		{
			'data': data,
			'new': data_new,
			'delete': delete_rows,
			'extra': fields.extra_information
		},
		ajaxReplyHandler
	);
	console.log(data);
}

function ajaxReplyHandler(x) {
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
}

function reloadRecords(fields) {
	$.get(
		fields.reloadURL,
		function(x) {
			eval("ret = " + x + ";");
			$('#' + fields.prefix + "_list").html(ret.html);
			new_rows = [];
			in_edit = [];
			delete_rows = [];
			delete_rows_color = [];
			$("#" + fields.prefix + "_table").tablesorter();
		}
	);
}

function editrow(fields, rowid) {
	if(in_edit.in_array(rowid)) {
		return;
	}
	in_edit.push(rowid);
	var spanid;
	for(var i = 0; i < fields.editfields.length; i++) {
		// generate fullname
		spanid = fields.prefix + '_' + fields.editfields[i].name + '_' + rowid;
		
		switch(fields.editfields[i].type) {
			case "text":
				generateTextfield(spanid, fields.editfields[i]);
				break;
			case "dropdown":
				generateDropdown(spanid, fields.editfields[i]);
				break;
			case "checkbox":
				generateCheckbox(spanid, fields.editfields[i]);
				break;
			default:
				console.warn("unknown type " + fields.editfields[i].type);
				break;	
		}
		
	}
}

function deleterow(fields, rowid) {
	var rowelement_id = fields.prefix + '_tablerow_' + rowid;
	
	if(!delete_rows.in_array(rowid)) {
		delete_rows_color[rowid] = $('#' + rowelement_id).css('background-color');
		$('#' + rowelement_id).css('background-color', '#f88');
		delete_rows.push(rowid);
	}
	else {
		delete_rows.splice(delete_rows.indexOf(rowid), 1);
		$('#' + rowelement_id).css('background-color', delete_rows_color[rowid]);
	}
}

function generateTextfield(spanid, field) {
	var value = $('#' + spanid).html();
	
	if(!value) {
		value = field.def;
	}
	
	$('#' + spanid).html('<input type="text" id="' + spanid + '_editor"' + (field.size ? ' size="' + field.size + '"' : "") + ' />');
	$('#' + spanid + '_editor').val(value);
}

function generateCheckbox(spanid, field) {
	var value = $('#' + spanid).html();
	
	// checked
	if(value == "[X]") {
		$('#' + spanid).html('<input type="checkbox" id="' + spanid +'_editor" checked />');
	}
	// unchecked
	else {
		$('#' + spanid).html('<input type="checkbox" id="' + spanid +'_editor" />');
	}
}

function generateDropdown(spanid, field) {
	var oldval = $('#' + spanid).html();
	var html = '<select id="' + spanid + '_editor">';
	for(var i = 0; i < field.options.length; i++) {
		html += '<option' + (field.options[i] == oldval ? ' selected' : '') + '>' + field.options[i] + '</option>';
	}
	html += '</select>';
	
	$('#' + spanid).html(html);
}

function addNewRow(fields) {
	// create newid
	var new_id = new_rows.length;
	new_rows.push(new_id);
	
	var spanid;
	var table = document.getElementById(fields.prefix + '_table');
	
	table.insertRow(table.rows.length);
	myrow = table.rows[table.rows.length-1];
	
	myrow.style.backgroundColor = '#cfc';
	
	for(var i = 0; i < table.rows[0].cells.length; i++) {
		myrow.insertCell(0);
	}
	
	for(var i = 0; i < fields.editfields.length; i++) {
		spanid = fields.prefix + '_' + fields.editfields[i].name + '_NEW' + new_id;
		myrow.cells[fields.editfields[i].offset].innerHTML = '<span id="' + spanid +'"></span>';
		
		switch(fields.editfields[i].type) {
			case "text":
				generateTextfield(spanid, fields.editfields[i]);
				break;
			case "dropdown":
				generateDropdown(spanid, fields.editfields[i]);
				break;
			default:
				console.warn("uknown type " + fields.editfields[i].type);
				break;	
		}
	}
	window.scroll(0,1000000);
}

function flushMsg() {
	$('#msg').html('');
}

function showMsg(type, msg) {
	var css;
	var icon;
	var msg;
	
	switch(type) {
		case 1:
			icon = 'information.png';
			css = 'box_info';
			break;
		case 2:
			icon = 'error.png';
			css = 'box_warning';
			break;
		case 3:
			icon = 'exclamation.png';
			css = 'box_error';
			break;
		case 4:
			icon = 'accept.png';
			css = 'box_ok';
			break;
	}
	
	msg = '<div class="' + css + ' msgbox"><img src="img/icons/' + icon + '" /> ' + msg + '</div>';
	$('#msg').html(msg + $('#msg').html());
}

$(document).ready(function() {
	window.setTimeout(
		function() {
			window.location.href = '/';
		},
		1000*60*60
	);
});

$.tablesorter.addParser({
    id: "natural",
    is: function(s) {
            return true;
    },
    format: function(s) {
    		var ip = s.match("[0-9]+\\.[0-9]+\\.[0-9]+\\.[0-9]+");
    		var ip_replace = "";
    		if(ip) {
        		ip = ip.toString();
				var ipsplit = ip.split(".");
				for(var c = 0; c < 4; c++) {
					switch(ipsplit[c].length) {
						case 1:
							ip_replace += "00" + ipsplit[c].toString();
							break;
						case 2:
							ip_replace += "0" + ipsplit[c].toString();
							break;
						case 3:
							ip_replace += ipsplit[c].toString();
							break;
					}
				}
				s = s.replace(ip.toString(), ip_replace);
				// $('#msg').append(ip_replace +  " ");
    		}
            return $.trim(s.toLowerCase());
    },
    type: "text"
});
