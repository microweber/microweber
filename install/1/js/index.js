function taskDone(e) {
	JSL.event(e).stop();
	var lnk = JSL.event(e).getTarget();
	
	loading();
	JSL.ajax(lnk.href+"&ajax=1").load(function(data) {
		loaded();
		var li = lnk.parentNode;
		li.parentNode.removeChild(li);
		showMessage(data);
	},"json");
}

function init() {
	$("a.done").click(taskDone);
}
