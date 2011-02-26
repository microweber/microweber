function getRow(ele) {
	var tr = ele.parentNode.parentNode;
	var trs = [tr];
	
	spacer = tr.previousSibling;

	//A stupid way to get to the next sibling - even if there is stuff in between
	var iterate = 0;
	var tr_count = 1;
	
	while(iterate<5) {
		tr = tr.nextSibling;
		if(!tr) break;

		if(tr.tagName && tr.tagName=='TR') {
			trs.push(tr);
			if(tr_count == 2) break; //We need 2 TR's - the description and the spacer
			tr_count++;
		}
		iterate++;
	}

	return trs;
}

function deleteItem(e) {
	JSL.event(e).stop();
	if(!confirm(t("Are you sure you want to delete this?"))) return;
	
	var lnk = JSL.event(e).getTarget();
	loading();
	JSL.ajax(lnk.href+"&ajax=1").load(function(data) {
		loaded();
		showMessage(data);
		if(data.error) return;

		var trs = getRow(lnk);
		for(var i in trs) {
			var tr = trs[i];
			tr.parentNode.removeChild(tr); //Remove the row of the deleted item.
		}
	},'json');
}
