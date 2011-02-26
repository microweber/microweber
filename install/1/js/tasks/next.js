var current_task = 0;
var tasks = [];
var skips = 0;

//Finds the index of the given ID in the tasks list
function findId(id) {
	for(var i=0; i<tasks.length; i++) {
		if(tasks[i] == id) return i;
	}
	return -1;
}

//Remove a task from the list - if its deleted or marked Done
function removeTask(lnk) {
	var id = lnk.href.match(/task=(\d+)\&?/)[1];

	var index = findId(id);
	if(index != -1) {
		hideCurrentTask();
		
		var new_tasks = [];
		for(var i=0; i<tasks.length; i++) {
			if(i != index)//Delete the index'th item
				new_tasks.push(tasks[i]);
		}
		tasks = new_tasks;
		
		if(!tasks.length) {
			$("controls").innerHTML = t("There are no more tasks left");
			
		} else {
			var id = tasks[current_task];
			if(id) $("task-"+id).className = "active";
		}
	}
}

//Deactivate the current task
function hideCurrentTask() {
	var id = tasks[current_task];
	if(id) $("task-"+id).className = "hidden";
}

//Find the next/previous valid task.
function changeCurrentIndex(amount) {
	skips ++; //Global - I know
	current_task += amount;

	//The loop back... The last item will bring you to the first and the first item gets you to the last.
	if(current_task >= tasks.length && amount == 1) current_task = 0;
	if(current_task < 0 && amount == -1) current_task = tasks.length - 1;

	if(skips >= 20) { //Too much recursion
		current_task = -1;
		alert("Internal Error");
		return;
	}

	if(!tasks[current_task]) changeCurrentIndex(amount);
	else skips = 0;
}

//Go to the next task
function nextTask() {
	if(tasks.length == 1) return;
	
	hideCurrentTask();
	changeCurrentIndex(1);
	
	var id = tasks[current_task];
	if(id) $("task-"+id).className = "active";
}

//Go the the previous task
function previousTask() {
	if(tasks.length == 1) return;
	hideCurrentTask();
	changeCurrentIndex(-1);
	var id = tasks[current_task];
	if(id) $("task-"+id).className = "active";
}

//Mark a task as done.
function taskDone(e) {
	JSL.event(e).stop();
	var lnk = JSL.event(e).getTarget();
	var data = {"success":"Done"};
	loading();
	JSL.ajax(lnk.href+"&ajax=1").load(function(data) {
		loaded();
		if(data.success) removeTask(lnk);
		showMessage(data);
	},"json");
}

//Delete a task.
function deleteTask(e) {
	JSL.event(e).stop();
	if(!confirm(t("Are you sure you want to delete this?"))) return;
	
	var lnk = JSL.event(e).getTarget(e);
	loading();
	JSL.ajax(lnk.href+"&ajax=1").load(function(data) {
		loaded();
		if(data.success) removeTask(lnk);
		showMessage(data);
	},'json');
}

function init() {
	var ids = $("#container div").get();
	for(var i=0; i<ids.length - 1; i++) { //The - 1 is intentional - remember the controls div?
		var id = ids[i].id.replace(/task\-/,"");
		tasks.push(id);
	}
	tasks.length = tasks.length;

	$(".done").click(taskDone);
	$(".delete").click(deleteTask);
	
	$("next-task").click(nextTask);
	$("previous-task").click(previousTask);
}
