//For Projects and Contexts
var controller = "project";
var new_form_attached = false;
var tds = new Array(); //Hold the innerHTML of all the rows 

function deleteItem(e) {
	JSL.event(e).stop();
	if(!confirm(t("Are you sure you want to delete this?"))) return;
	
	var lnk = JSL.event(e).getTarget();
	loading();
	JSL.ajax(lnk.href+"&ajax=1").load(function(data) {
		loaded();
		showMessage(data);
		if(data.error) return;
		
		var tr = lnk.parentNode.parentNode;
		tr.parentNode.removeChild(tr); //Remove the row of the deleted item.
	},'json');
}

function cancelRename(id) {
	$("cell_"+id).innerHTML = tds[id]; //Restore the old innerHTML
}
function rename(id) {
	var new_name = $("rename_"+id).value;
	if(!new_name) {
		showMessage({"error":t("Please specify the new name")});
		return;
	}
	loading();
	JSL.ajax("edit.php?"+controller+"="+id+"&name="+new_name+"&ajax=1&action="+t("Edit")).load(function(data) {
		loaded();
		showMessage(data);
		if(data.error) return;

		$("cell_"+id).innerHTML = tds[id]; //Get the old innerHTML from the Global array
		var lnk = $("cell_"+id).getElementsByTagName("a")[0];
		lnk.innerHTML = new_name; //And insert the new name in its place.
	},'json');
}
function renameItem(e) {
	JSL.event(e).stop();
	
	var lnk = JSL.event(e).getTarget();
	var tr = lnk.parentNode.parentNode;
	var td = tr.getElementsByTagName("td")[0];
	var item_name = tr.getElementsByTagName("a")[0].innerHTML; //Get the existing name
	
	if(lnk.href.match(/context/)) {
		var item_id = lnk.href.match(/context\=(\d+)/)[1];//Get the ID of the item from the link
	} else {
		var item_id = lnk.href.match(/project\=(\d+)/)[1];
	}
	
	tds[item_id] = td.innerHTML; //Put the current innerHTML of the cell into a Global array
	
	//Insert our new HTML.
	td.innerHTML = "<input id='rename_"+item_id+"' type='text' value='"+item_name+"' /> "
			+ "<a href='javascript:rename("+item_id+");'>"+t('Rename')+"</a> | "
			+ "<a href='javascript:cancelRename("+item_id+");'>"+t('Cancel')+"</a>";
}

function newItem(e) {
	var table = document.getElementsByTagName("table")[0];
	var template_node = document.getElementsByTagName("tr")[1]; //Get an existing row
	if(!template_node) template_node = document.getElementsByTagName("tr")[0]; //Try to get the 2nd row first - this is for projects where the 1st row don't have a delete function.

	if(template_node) JSL.event(e).stop();
	else return;
	
	var new_name = $("name").value;
	if(!new_name) {
		showMessage({"error":t("Please specify the new name")});
		return;
	}

	loading();
	JSL.ajax("new.php?name="+new_name+"&ajax=1&action="+t("Create")).load(function(data) {
		loaded();
		showMessage(data);
		if(data.error) return;
		$("name").value = "";
		$("name").focus();
		var row = template_node.cloneNode(true); //And clone it. Wonder how many browsers supports this
		
		var lnks = row.getElementsByTagName("a");
		lnks[0].innerHTML = new_name; //Put in the new name.
		for(var i=0; i<lnks.length; i++) {
			lnks[i].href = lnks[i].href.replace(/context=(\d+)/g,"context="+data.id);
			lnks[i].href = lnks[i].href.replace(/contexts\[\]=(\d+)/g,"contexts[]="+data.id);
			lnks[i].href = lnks[i].href.replace(/project=(\d+)/g,"project="+data.id);
		}
		table.appendChild(row);

		attachHandlers();
	},'json');

}

function attachHandlers() {
	var delete_links = $("a.delete").get();
	var edit_links = $("a.edit").get();
	var len = delete_links.length;
	
	for(var i=0; i<len; i++) {
		if(delete_links[i].onclick) continue;
		JSL.dom(delete_links[i]).click(deleteItem);
		JSL.dom(edit_links[i]).click(renameItem);
	}
	
	if(!new_form_attached) {
		$("new_item").on("submit",newItem);
		new_form_attached = true;
	}
}
