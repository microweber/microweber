function init() {
	$("fetch-by-email").on("submit", function(e) {
		if(!$("email").value.match(/[\w\+\-\.]+\@[\w\+\-\.]+\.[\w\+\.]{2,5}/)) {
			alert(t("Please enter a valid email address"));
			JSL.event(e).stop();
		}
	});
} 
