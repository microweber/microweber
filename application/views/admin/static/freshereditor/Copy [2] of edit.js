
 
 
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

function mw_make_columns($el_id){
	if($el_id == undefined || $el_id == 'undefined' ){
		$el_id = window.mw_row_id;
	} else {
	window.mw_row_id = 	$el_id;
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
			
 
		 
			
			
			$('.edit').sortable('destroy');
			$('.col').sortable('destroy');
			$('.column').sortable('destroy');
			$('.row').sortable('destroy');
			$('.edit').sortable({
    items: '.row:not(.disabled,.col)',
	 forcePlaceholderSize: true,
	 tolerance: 'pointer',
	  revert: true,
	placeholder: "ui-state-highlight-row",
	 connectWith: '.edit'
});
			
					$('.column:not(.disabled)').sortable({
    items: '.col',
//  revert: true,
	  tolerance: 'pointer',
	   forceHelperSize: true,
	 // placeholder: "ui-state-highlight",
	   forcePlaceholderSize: true,
	 connectWith: '.column' ,
	   helper: 'clone',
	   change: function(event, ui) { 
	  
	   },
      sort: function(e,ui){
		  
                /* The trick is that jQuery hides the inline element while user drags an absolute positioned clone so we want to deal only with the visible elements. Index order + 1 = UI order.*/
                $(ui.placeholder).html(Number($(".col:visible").index(ui.placeholder)+1));
              }
	 
});
			$( ".column" ).resizable({
								   handles: 'e',
								   resize: function(event, ui) {
											$(this).css('height', 'auto');
										}
								  });
			
			
				$( ".col" ).resizable({
								   //handles: 'e',
								   //ghost: true,
								//   containment: ".column",
								   resize: function(event, ui) {
											$(this).css('height', 'auto');
										}
								  });
			
			
			 $(".row,.col", '.edit').disableSelection();
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







































 
		
 
 
 
 
 // version 1.4.0
// http://welcome.totheinter.net/columnizer-jquery-plugin/
// created by: Adam Wulf adam.wulf@gmail.com

(function($){

 $.fn.columnize = function(options) {


	var defaults = {
		// default width of columnx
		width: 400,
		// optional # of columns instead of width
		columns : false,
		// true to build columns once regardless of window resize
		// false to rebuild when content box changes bounds
		buildOnce : false,
		// an object with options if the text should overflow
		// it's container if it can't fit within a specified height
		overflow : false,
		// this function is called after content is columnized
		doneFunc : function(){},
		// if the content should be columnized into a 
		// container node other than it's own node
		target : false,
		// re-columnizing when images reload might make things
		// run slow. so flip this to true if it's causing delays
		ignoreImageLoading : true,
		// should columns float left or right
		float : "left",
		// ensure the last column is never the tallest column
		lastNeverTallest : false
	};
	var options = $.extend(defaults, options);

    return this.each(function() {
	    var $inBox = options.target ? $(options.target) : $(this);
		var maxHeight = $(this).height();
		var $cache = $('<div></div>'); // this is where we'll put the real content
		var lastWidth = 0;
		var columnizing = false;
		$cache.append($(this).children().clone(true));
	    
	    // images loading after dom load
	    // can screw up the column heights,
	    // so recolumnize after images load
	    if(!options.ignoreImageLoading && !options.target){
	    	if(!$inBox.data("imageLoaded")){
		    	$inBox.data("imageLoaded", true);
		    	if($(this).find("img").length > 0){
		    		// only bother if there are
		    		// actually images...
			    	var func = function($inBox,$cache){ return function(){
				    	if(!$inBox.data("firstImageLoaded")){
				    		$inBox.data("firstImageLoaded", "true");
					    	$inBox.empty().append($cache.children().clone(true));
					    	$inBox.columnize(options);
				    	}
			    	}}($(this), $cache);
				    $(this).find("img").one("load", func);
				    $(this).find("img").one("abort", func);
				    return;
		    	}
	    	}
	    }
	    
		$inBox.empty();
		
		columnizeIt();
		
		if(!options.buildOnce){
			$(window).resize(function() {
				if(!options.buildOnce && $.browser.msie){
					if($inBox.data("timeout")){
						clearTimeout($inBox.data("timeout"));
					}
					$inBox.data("timeout", setTimeout(columnizeIt, 200));
				}else if(!options.buildOnce){
					columnizeIt();
				}else{
					// don't rebuild
				}
			});
		}
		
		/**
		 * return a node that has a height
		 * less than or equal to height
		 *
		 * @param putInHere, a dom element
		 * @$pullOutHere, a jQuery element
		 */
		function columnize($putInHere, $pullOutHere, $parentColumn, height){
			while($parentColumn.height() < height &&
				  $pullOutHere[0].childNodes.length){
				$putInHere.append($pullOutHere[0].childNodes[0]);
			}
			if($putInHere[0].childNodes.length == 0) return;
			
			// now we're too tall, undo the last one
			var kids = $putInHere[0].childNodes;
			var lastKid = kids[kids.length-1];
			$putInHere[0].removeChild(lastKid);
			var $item = $(lastKid);
			
			
			if($item[0].nodeType == 3){
				// it's a text node, split it up
				var oText = $item[0].nodeValue;
				var counter2 = options.width / 18;
				if(options.accuracy)
				counter2 = options.accuracy;
				var columnText;
				var latestTextNode = null;
				while($parentColumn.height() < height && oText.length){
					if (oText.indexOf(' ', counter2) != '-1') {
						columnText = oText.substring(0, oText.indexOf(' ', counter2));
					} else {
						columnText = oText;
					}
					latestTextNode = document.createTextNode(columnText);
					$putInHere.append(latestTextNode);
					
					if(oText.length > counter2){
						oText = oText.substring(oText.indexOf(' ', counter2));
					}else{
						oText = "";
					}

				}
				if($parentColumn.height() >= height && latestTextNode != null){
					// too tall :(
					$putInHere[0].removeChild(latestTextNode);
					oText = latestTextNode.nodeValue + oText;
				}
				if(oText.length){
					$item[0].nodeValue = oText;
				}else{
					return false; // we ate the whole text node, move on to the next node
				}
			}
			
			if($pullOutHere.children().length){
				$pullOutHere.prepend($item);
			}else{
				$pullOutHere.append($item);
			}
			
			return $item[0].nodeType == 3;
		}
		
		function split($putInHere, $pullOutHere, $parentColumn, height){
			if($pullOutHere.children().length){
				$cloneMe = $pullOutHere.children(":first");
				$clone = $cloneMe.clone(true);
				if($clone.attr("nodeType") == 1 && !$clone.hasClass("dontend")){ 
					$putInHere.append($clone);
					if($clone.is("img") && $parentColumn.height() < height + 20){
						$cloneMe.remove();
					}else if(!$cloneMe.hasClass("dontsplit") && $parentColumn.height() < height + 20){
						$cloneMe.remove();
					}else if($clone.is("img") || $cloneMe.hasClass("dontsplit")){
						$clone.remove();
					}else{
						$clone.empty();
						if(!columnize($clone, $cloneMe, $parentColumn, height)){
							if($cloneMe.children().length){
								split($clone, $cloneMe, $parentColumn, height);
							}
						}
						if($clone.get(0).childNodes.length == 0){
							// it was split, but nothing is in it :(
							$clone.remove();
						}
					}
				}
			}
		}
		
		
		function singleColumnizeIt() {
			if ($inBox.data("columnized") && $inBox.children().length == 1) {
				return;
			}
			$inBox.data("columnized", true);
			$inBox.data("columnizing", true);
			
			$inBox.empty();
			$inBox.append($("<div class='first last column' style='width:98%; padding: 3px; float: " + options.float + ";'></div>")); //"
			$col = $inBox.children().eq($inBox.children().length-1);
			$destroyable = $cache.clone(true);
			if(options.overflow){
				targetHeight = options.overflow.height;
				columnize($col, $destroyable, $col, targetHeight);
				// make sure that the last item in the column isn't a "dontend"
				if(!$destroyable.children().find(":first-child").hasClass("dontend")){
					split($col, $destroyable, $col, targetHeight);
				}
				
				while(checkDontEndColumn($col.children(":last").length && $col.children(":last").get(0))){
					var $lastKid = $col.children(":last");
					$lastKid.remove();
					$destroyable.prepend($lastKid);
				}

				var html = "";
				var div = document.createElement('DIV');
				while($destroyable[0].childNodes.length > 0){
					var kid = $destroyable[0].childNodes[0];
					for(var i=0;i<kid.attributes.length;i++){
						if(kid.attributes[i].nodeName.indexOf("jQuery") == 0){
							kid.removeAttribute(kid.attributes[i].nodeName);
						}
					}
					div.innerHTML = "";
					div.appendChild($destroyable[0].childNodes[0]);
					html += div.innerHTML;
				}
				var overflow = $(options.overflow.id)[0];
				overflow.innerHTML = html;

			}else{
				$col.append($destroyable);
			}
			$inBox.data("columnizing", false);
			
			if(options.overflow){
				options.overflow.doneFunc();
			}
			
		}
		
		function checkDontEndColumn(dom){
			if(dom.nodeType != 1) return false;
			if($(dom).hasClass("dontend")) return true;
			if(dom.childNodes.length == 0) return false;
			return checkDontEndColumn(dom.childNodes[dom.childNodes.length-1]);
		}
		
		function columnizeIt() {
			if(lastWidth == $inBox.width()) return;
			lastWidth = $inBox.width();
			
			var numCols = Math.round($inBox.width() / options.width);
			if(options.columns) numCols = options.columns;
//			if ($inBox.data("columnized") && numCols == $inBox.children().length) {
//				return;
//			}
			if(numCols <= 1){
				return singleColumnizeIt();
			}
			if($inBox.data("columnizing")) return;
			$inBox.data("columnized", true);
			$inBox.data("columnizing", true);
			
			$inBox.empty();
			$inBox.append($("<div style='width:" + (Math.round(100 / numCols) - 2)+ "%; padding: 3px; float: " + options.float + ";'></div>")); //"
			$col = $inBox.children(":last");
			$col.append($cache.clone());
			maxHeight = $col.height();
			$inBox.empty();
			
			var targetHeight = maxHeight / numCols;
			var firstTime = true;
			var maxLoops = 3;
			var scrollHorizontally = false;
			if(options.overflow){
				maxLoops = 1;
				targetHeight = options.overflow.height;
			}else if(options.height && options.width){
				maxLoops = 1;
				targetHeight = options.height;
				scrollHorizontally = true;
			}
			
			for(var loopCount=0;loopCount<maxLoops;loopCount++){
				$inBox.empty();
				var $destroyable;
				try{
					$destroyable = $cache.clone(true);
				}catch(e){
					// jquery in ie6 can't clone with true
					$destroyable = $cache.clone();
				}
				$destroyable.css("visibility", "hidden");
				// create the columns
				for (var i = 0; i < numCols; i++) {
					/* create column */
					var className = (i == 0) ? "first column" : "column";
					var className = (i == numCols - 1) ? ("last " + className) : className;
					$inBox.append($("<div class='" + className + "' style='width:" + (Math.round(100 / numCols) - 2)+ "%; float: " + options.float + ";'></div>")); //"
				}
				
				// fill all but the last column (unless overflowing)
				var i = 0;
				while(i < numCols - (options.overflow ? 0 : 1) || scrollHorizontally && $destroyable.children().length){
					if($inBox.children().length <= i){
						// we ran out of columns, make another
						$inBox.append($("<div class='" + className + "' style='width:" + (Math.round(100 / numCols) - 2)+ "%; float: " + options.float + ";'></div>")); //"
					}
					var $col = $inBox.children().eq(i);
					columnize($col, $destroyable, $col, targetHeight);
					// make sure that the last item in the column isn't a "dontend"
					if(!$destroyable.children().find(":first-child").hasClass("dontend")){
						split($col, $destroyable, $col, targetHeight);
					}else{
//						alert("not splitting a dontend");
					}
					
					while(checkDontEndColumn($col.children(":last").length && $col.children(":last").get(0))){
						var $lastKid = $col.children(":last");
						$lastKid.remove();
						$destroyable.prepend($lastKid);
					}
					i++;
				}
				if(options.overflow && !scrollHorizontally){
					var IE6 = false /*@cc_on || @_jscript_version < 5.7 @*/;
					var IE7 = (document.all) && (navigator.appVersion.indexOf("MSIE 7.") != -1);
					if(IE6 || IE7){
						var html = "";
						var div = document.createElement('DIV');
						while($destroyable[0].childNodes.length > 0){
							var kid = $destroyable[0].childNodes[0];
							for(var i=0;i<kid.attributes.length;i++){
								if(kid.attributes[i].nodeName.indexOf("jQuery") == 0){
									kid.removeAttribute(kid.attributes[i].nodeName);
								}
							}
							div.innerHTML = "";
							div.appendChild($destroyable[0].childNodes[0]);
							html += div.innerHTML;
						}
						var overflow = $(options.overflow.id)[0];
						overflow.innerHTML = html;
					}else{
						$(options.overflow.id).empty().append($destroyable.children().clone(true));
					}
				}else if(!scrollHorizontally){
					// the last column in the series
					$col = $inBox.children().eq($inBox.children().length-1);
					while($destroyable.children().length) $col.append($destroyable.children(":first"));
					var afterH = $col.height();
					var diff = afterH - targetHeight;
					var totalH = 0;
					var min = 10000000;
					var max = 0;
					var lastIsMax = false;
					$inBox.children().each(function($inBox){ return function($item){
						var h = $inBox.children().eq($item).height();
						lastIsMax = false;
						totalH += h;
						if(h > max) {
							max = h;
							lastIsMax = true;
						}
						if(h < min) min = h;
					}}($inBox));

					var avgH = totalH / numCols;
					if(options.lastNeverTallest && lastIsMax){
						// the last column is the tallest
						// so allow columns to be taller
						// and retry
						targetHeight = targetHeight + 30;
						if(loopCount == maxLoops-1) maxLoops++;
					}else if(max - min > 30){
						// too much variation, try again
						targetHeight = avgH + 30;
					}else if(Math.abs(avgH-targetHeight) > 20){
						// too much variation, try again
						targetHeight = avgH;
					}else {
						// solid, we're done
						loopCount = maxLoops;
					}
				}else{
					// it's scrolling horizontally, fix the width/classes of the columns
					$inBox.children().each(function(i){
						$col = $inBox.children().eq(i);
						$col.width(options.width + "px");
						if(i==0){
							$col.addClass("first");
						}else if(i==$inBox.children().length-1){
							$col.addClass("last");
						}else{
							$col.removeClass("first");
							$col.removeClass("last");
						}
					});
					$inBox.width($inBox.children().length * options.width + "px");
				}
				$inBox.append($("<br style='clear:both;'>"));
			}
			$inBox.find('.column').find(':first.removeiffirst').remove();
			$inBox.find('.column').find(':last.removeiflast').remove();
			$inBox.data("columnizing", false);

			if(options.overflow){
				options.overflow.doneFunc();
			}
			options.doneFunc();
		}
    });
 };
})(jQuery);
 
 
 
 