function taskDone(e) {
	JSL.event(e).stop();
	var lnk = JSL.event(e).getTarget();
	loading();
	JSL.ajax(lnk.href+"&ajax=1").load(function(data) {
		loaded();
		lnk.href = "";
		showMessage(data);
		if(data.error) return;

		//Once the task is mark done, nothing should be done - even when clicked
		$(lnk).click(function(e) {
			JSL.event(e).stop();
		});
		var trs = getRow(lnk);
		for(var i in trs) {
			//We must change the row
			var tr = trs[i];
			tr.className = tr.className.toString().replace(/type\-\w+/,"type-done");
		}
	},"json");
}

function init() {
	$("a.done").click(taskDone);
	$("a.delete").click(deleteItem);
}
