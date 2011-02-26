function changePassword() {
	$("change_password_question").hide("none");
	$("password").show("inline");
}
function init() {
	$("delete").click(function(e) {
		if(!confirm(t("Are you sure you want to delete this user?"))) {
			JSL.event(e).stop();
		}
	});
	
	$("user-details").on("submit", function(e) {
		if(!$("email").value.match(/[\w\+\-\.]+\@[\w\+\-\.]+\.[\w\+\.]{2,5}/)) {
			alert(t("Please enter a valid email address"));
			JSL.event(e).stop();
		}
	});
}