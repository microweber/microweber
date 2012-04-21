
 
 
window.mw_editables_created = false;
window.mw_element_id = false;
function mw_delete_element($el_id){
	if($el_id == undefined || $el_id == 'undefined' ){
		$el_id = window.mw_element_id;
	}
//	alert($el_id);
	$($el_id).fadeOut().remove();
	$('#'+$el_id).fadeOut().remove();

}
function mw_make_css_editor($el_id){
	if($el_id == undefined || $el_id == 'undefined' ){
		$el_id = window.mw_element_id;
	} else {
	window.mw_element_id = 	$el_id;
	}
	$(".mw-layout-edit-curent-element").html($el_id);

}
function mw_make_editables(){
				 
				
	
 
	
				 if(window.mw_drag_started == false && window.mw_handle_hover != true ){
				window.mw_sortables_created = false;
			 if(window.mw_editables_created == false){
				$(".edit [draggable='true']").unbind();
				$(".edit [draggable='true']").removeAttr('draggable');
	$(".edit").freshereditor("edit", true);
			  window.mw_editables_created = true
			  $("#mw-layout-edit-site-top-bar-r").html("Text edit");
			  
			 }

	 }
				 
				 
}




 window.mw_sortables_created = false;
 window.mw_drag_started = false;
function mw_remove_editables(){
 
	 window.mw_editing_started  = false;
	window.mw_editables_created = false;
	 $(".edit").freshereditor("edit", false);
 
}


function init_sortables(){
	       // $('#mercury_iframe').contents().find('.edit').html('Hey, i`ve changed content of  body>! Yay!!!');
		   
			
		
			mw_remove_editables()
			
			  if(window.mw_sortables_created == false){
			  
			// $(".edit *").attr('draggable', true);
			 
			// $('.edit').sortable().bind('sortupdate', function() {});
			//$(".row").multicol({colNum: 3, colMargin: 20});
			
 columnConform();
			
			
			
			$('.edit').sortable('destroy');
			$('.col').sortable('destroy');
			$('.row').sortable('destroy');
			$('.edit').sortable({
    items: '.row:not(.disabled,.col)',
	 forcePlaceholderSize: true,
	 tolerance: 'pointer',
	  revert: true,
	placeholder: "ui-state-highlight-row",
	 connectWith: '.edit'
});
			
					$('.row:not(.disabled)').sortable({
    items: '.col',
//  revert: true,
	  tolerance: 'pointer',
	 // forceHelperSize: true,
	// placeholder: "ui-state-highlight",
	  //forcePlaceholderSize: true,
	 connectWith: '.row' ,
	  helper: 'clone',
      sort: function(e,ui){
                /* The trick is that jQuery hides the inline element while user drags an absolute positioned clone so we want to deal only with the visible elements. Index order + 1 = UI order.*/
               // $(ui.placeholder).html(Number($(".col:visible").index(ui.placeholder)+1));
              }
	 
});
			$( ".row" ).resizable({
								   handles: 'e',
								   resize: function(event, ui) {
											$(this).css('height', 'auto');
										}
								  });
			
			
			 $(".row,.col", '.edit').disableSelection();
			
			
			
			$(".row,.col").die('mousedown');
			
			  $(".row,.col").live(
        'mousedown',
        function (e) {
          //  if(!dragging){ 
			 
$el_id = $(this).attr('id');
if($el_id == undefined || $el_id == 'undefined' ){
	//alert($el_id);
$el_id = 'mw-element-'+new Date().getTime();
  $(this).attr('id', $el_id);
}
window.mw_element_id = $el_id;
mw_make_css_editor($el_id)

e.stopPropagation();
            //
            //    $(this).removeClass("ui-state-hover");
          
		  
			//}
		  
        });
			
			$(".row,.col").die('hover');
			 $(".row,.col").live(
        'hover',
        function (e) {
     //    $(".mw-sorthandle").remove();
 $has = $(this).children(":first").hasClass("mw-sorthandle");
if($has == false){
	$(this).prepend("<div class='mw-sorthandle'><span>&nbsp;</span>");
}

//e.stopPropagation();
          
		  
        });
			 
			 $(".row", '.edit').mouseover(function(){
   $has = $(this).children(":first").hasClass("mw-sorthandle");
   if($has == false){
	$(this).prepend("<div class='mw-sorthandle mw-sorthandle-row'><span>&nbsp;</span>");
}

})
			 
			 
			 		 $(".col", '.edit').mouseover(function(){
   $has = $(this).children(":first").hasClass("mw-sorthandle");
   if($has == false){
	$(this).prepend("<div class='mw-sorthandle mw-sorthandle-col'><span>&nbsp;</span>");
}

})
			 
			
			
			//<div class='sorthandle'><span>&nbsp;</span></div>

			
			
			   $("#mw-layout-edit-site-top-bar-r").html("Drag and drop edit");
			  window.mw_sortables_created = true
			 
			/* $('.edit').sortable('destroy');
			 $('.edit').sortable({ 
			//  appendTo: '.row' ,											   
			 items: '.module' ,
			   
			 handle: '.col, a, .box, .col > *, .module, .module > *' ,
			 //  containment: '.row',
			//   containment: 'parent',
			 //cancel: '.module > *' ,
			  forceHelperSize: true,
			  iframeFix: true,
			  connectWith: ".edit, .edit div, .edit p, .edit br, .edit ul, .edit h1, .edit h2, .edit h3",
			 forcePlaceholderSize: true
			
			 }).disableSelection();*/
			  window.mw_sortables_created = true
			 
			  }
			 
		 


}
	
	
	
		       $('.module', '.edit').live('blur', function () {

 });
	
	 $('.module' , '.edit').live('mousenter',function(e) {
					$(this).children('[draggable]').removeAttr('draggable')							  
		});										  
												  
	
 $('.module' , '.edit').live('click',function(e) {
	 
		 
		
		 
		  init_sortables()
		 
		 
		
		  window.mw_making_sortables = false;
		  
		$clicked_on_module = 	$(this).attr('module_id');
		  if($clicked_on_module == undefined || $clicked_on_module == ''){
			  $clicked_on_module = 	$(this).attr('module_id', 'default');
			  
		  }
		
		 if (window.console != undefined) {
				console.log('click on module 1 ' + $clicked_on_module );	
			}
		
		
		  if($clicked_on_module == undefined || $clicked_on_module == ''){
				$clicked_on_module = 	$(this).parents('.module').attr('module_id');
		  }
		  
		  if($clicked_on_module == undefined || $clicked_on_module == ''){
			  $clicked_on_module = 	$(this).parents('.module').attr('module_id', 'default');
			  
		  }
		  
		  $('.mw_non_sortable').removeClass('mw_non_sortable');
		 
		 
		  
		// alert($clicked_on_module);
		 
		 
		 
    e.preventDefault();
			//event.preventDefault(); // this prevents the original href of the link from being opened
			e.stopPropagation(); // this prevents the click from triggering click events up the DOM from this element
			return false;
	 
});








































// these are (ruh-roh) globals. You could wrap in an
		// immediately-Invoked Function Expression (IIFE) if you wanted to...
		var currentTallest = 0,
		    currentRowStart = 0,
		    rowDivs = new Array();
		
		function setConformingHeight(el, newHeight) {
			// set the height to something new, but remember the original height in case things change
			el.data("originalHeight", (el.data("originalHeight") == undefined) ? (el.height()) : (el.data("originalHeight")));
			el.height(newHeight);
		}
		
		function getOriginalHeight(el) {
			// if the height has changed, send the originalHeight
			return (el.data("originalHeight") == undefined) ? (el.height()) : (el.data("originalHeight"));
		}
		
		function columnConform() {
		var currentTallest = 0,
		    currentRowStart = 0,
		    rowDivs = new Array();
			// find the tallest DIV in the row, and set the heights of all of the DIVs to match it.
			$('.row > .column', '.edit').each(function() {
			
				// "caching"
				var $el = $(this);
				
				var topPosition = $el.position().top;
		
				if (currentRowStart != topPosition) {
		
					// we just came to a new row.  Set all the heights on the completed row
					for(currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) setConformingHeight(rowDivs[currentDiv], currentTallest);
		
					// set the variables for the new row
					rowDivs.length = 0; // empty the array
					currentRowStart = topPosition;
					currentTallest = getOriginalHeight($el);
					rowDivs.push($el);
		
				} else {
		
					// another div on the current row.  Add it to the list and check if it's taller
					rowDivs.push($el);
					currentTallest = (currentTallest < getOriginalHeight($el)) ? (getOriginalHeight($el)) : (currentTallest);
		
				}
				// do the last row
				for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) setConformingHeight(rowDivs[currentDiv], currentTallest);
		
			});
		
		}
		
		
 
 
 
 
 
 
 