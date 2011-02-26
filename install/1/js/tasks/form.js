function validate(e) {
	if(!$("name").value) {
		showMessage({"error":t("Please enter the name of the task")});
		JSL.event(e).stop();
	}
}

function init() {
	$("task_frm").on("submit",validate);
}
calendar.init({
	'input':'due_on'
});