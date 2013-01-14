$(document).ready(function() {

	//When page loads...
	$(".tab-content-1").hide(); //Hide all content
	$("ul.tab-menu li:first").addClass("active").show(); //Activate first tab
	$(".tab-content-1:first").show(); //Show first tab content

	//On Click Event
	$("ul.tab-menu li").click(function() {

		$("ul.tab-menu li").removeClass("active"); //Remove any "active" class
		$(this).addClass("active"); //Add "active" class to selected tab
		$(".tab-content-1").hide(); //Hide all tab content

		var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
		$(activeTab).fadeIn(); //Fade in the active ID content
		return false;
	});

});