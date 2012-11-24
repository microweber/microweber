$.noConflict();

 
jQuery(window).load(function () {
	//COLUMNS HEIGHTS
	function equalHeight(){
		var maxHeight = 0;
		jQuery("#front-page-presentation-row-1 div ul").each(function(index){
			if (jQuery(this).height() > maxHeight) maxHeight = jQuery(this).height();
		});
		jQuery("#front-page-presentation-row-1 div.sep").height(maxHeight);
	}
	equalHeight();
});




/* raikov */


jQuery(document).ready(function(){

  jQuery(".newpwd:visible").before("<div class='c'>&nbsp;</div>");

});
