$(document).ready(function() {
	$(".box .box-toggle").bind('click', function(){boxToggle(this);return false;});
	$(".remove-btn").bind('click', function(){messageRemove(this);return false;});
});

function boxToggle(that) {
	content_box = $(that).parent().parent().parent().find(".box-content");
	box = $(content_box).parent();
	if($(content_box).css("display") == "none") {
		$(content_box).slideDown(200, function(){
			$(box).removeClass("closed").addClass("open");
		});
	}
	else {
		$(content_box).slideUp(200, function(){
			$(box).removeClass("open").addClass("closed");
		});
	}
}

function messageRemove(that) {
	$(that).parent().slideUp(200);
}