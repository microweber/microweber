
 
 
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
function mw_make_cols($numcols){
	
	
	
	
	
	
	
	$el_id = window.mw_row_id ;
	if($el_id != undefined && $el_id != false && $el_id != 'undefined'  ){
	window.mw_sortables_created = false;
	// $('#'+$el_id).columnize({ columns: $numcols, target:'#'+$el_id, buildOnce:true  });
	
	 $exisintg_num = $('#'+$el_id).children(".column").size();

	
	if($numcols == 0){
	$numcols = 1;
	}
	$exisintg_num = parseInt($exisintg_num);
	$numcols = parseInt($numcols);

	
	if($exisintg_num == 0){
	$exisintg_num = 1;
	}
	
	
		if($numcols != $exisintg_num){
	
	
	
			if ( window.console && window.console.log )
				{
					window.console.log( '  $exisintg_num ' + $exisintg_num + '       $numcols ' + $numcols  );
				}
				
	 



		 
			if($numcols > $exisintg_num){
			
						for (i=$exisintg_num;i<$numcols;i++){
							$el_id_column1 = 'mw-column-'+new Date().getTime()+Math.floor(Math.random()*11);
					 $('<div class="column"  id="'+$el_id_column1+'">'+window.mw_empty_column_placeholder+'</div>').appendTo('#'+$el_id);
						}
				
			} else {
			
			
			$cols_to_remove = $exisintg_num - $numcols;
			if ( window.console && window.console.log )
				{
					window.console.log( '$cols_to_remove' + $cols_to_remove );
				}
				
				
				if($cols_to_remove > 0){
					
					for (i=$cols_to_remove;i> 0;i--){
						//for (i=0;i<=$cols_to_remove;i++){
						 $ch_n = parseInt($exisintg_num) - parseInt(i) ;
						 if($cols_to_remove >= 1){
						 if ( window.console && window.console.log ){
							window.console.log( '$removinc child col' + '#'+$el_id +">div.column:nth-child("+$ch_n+")" );
							}
							 
					$('#'+$el_id ).children(".column:eq("+$ch_n+")").fadeOut('slow').remove(); 
		 
						 }
							
							
						}
					
					
				}
			}
		
		
		
		
		
		
			$exisintg_num = $('#'+$el_id).children(".column").size();
		
		$eq_w = 100/$exisintg_num;
		 $('#'+$el_id).children(".column").width($eq_w+'%');
		 $('#'+$el_id).children(".column").css('float', 'left');
			$('#'+$el_id).equalHeights();
			 
 		init_sortables()
		
		
		
		
	
			
			}
	
	
	
	
	
	
	
	
	
		}
	
	
	
	
}

function mw_make_row_editor($el_id){
	
	if($el_id == undefined || $el_id == 'undefined' ){
		$el_id = window.mw_row_id;
	} else {
	window.mw_row_id = 	$el_id;
	}
	$(".mw-layout-edit-curent-row-element").html($el_id);
	
	$exisintg_num = $('#'+$el_id).children(".column").size();
	$(".mw-make-cols").removeClass('active');
	$(".mw-make-cols-"+$exisintg_num).addClass('active');
	
	// alert($exisintg_num);
}
function mw_load_new_dropped_modules(){
	$need_re_init = false; 
	$(".module_draggable", '.edit').each(function (c) {
               
			   $name =  $(this).attr("data-module-name");
			   // $(this).css("margin", '10px');
			    // $(this).after();
				$el_id_new = 'mw-col-'+new Date().getTime()+Math.floor(Math.random()*11);
				$(this).after("<div class='col' id='"+$el_id_new+"'></div>");
				
			//  $(this).attr('id', $el_id_column);	
				
				
				
				mw.load_module($name, '#'+$el_id_new);
				
				$(this).fadeOut().remove();
				$need_re_init = true; 
				
            })
			
			
			
			if($need_re_init == false){
				init_sortables()
			}
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
				
				
				 $( '.mw-sorthandle' ).remove();
				 	$('.edit').sortable('destroy');
			$('.col').sortable('destroy');
			$('.column').sortable('destroy');
			$('.row').sortable('destroy');
			 $(".row,.col", '.edit').enableSelection();
			  $(".mw-sorthandle", '.edit').disableSelection();
				
				
	$(".edit").freshereditor("edit", true);
			  window.mw_editables_created = true
			  $("#mw-layout-edit-site-top-bar-r").html("Text edit");
			  
			 }

	 }
				 
				 
}




 window.mw_sortables_created = false;
 window.mw_drag_started = false;
 window.mw_row_id = false;
 window.mw_empty_column_placeholder ='<div class="empty ui-state-highlight"><span>Please drag items here</span></div>';
function mw_remove_editables(){
 
	 window.mw_editing_started  = false;
	window.mw_editables_created = false;
	 $(".edit").freshereditor("edit", false);
 
}
  function saveOrder() {
				//var data = $("#gallery li").map(function() { return $(this).data("itemid"); }).get();
		      //  $.post("example.php", { "ids[]": data });
		    };

function init_sortables(){
	       // $('#mercury_iframe').contents().find('.edit').html('Hey, i`ve changed content of  body>! Yay!!!');
		   
			
		
			mw_remove_editables()
			
			  if(window.mw_sortables_created == false){
 
		 
		// $(".row").dragsort({ dragSelector: "", dragEnd: saveOrder, placeHolderTemplate: "<div class='col'><div>DROP HERE</div></div>" });
 //$(".column").dragsort("destroy");
		  		//$(".column").dragsort({ dragSelector: ".col", dragBetween: true, dragEnd: saveOrder, placeHolderTemplate: "<div class='col'><div>DROP HERE</div></div>" });
	var place1 = $('<div class="empty ui-state-highlight" style="display:none;"><span>Please drag items here</span></div>');			
     var place2 = window.mw_empty_column_placeholder;
  $(".column", '.edit').each(function (c) {
                if ($("div", this).size() == 0) {
               //     $(this).html(place2);
                }
            })
  
		 	$('.edit').sortable('destroy');
			$('.col').sortable('destroy');
			$('.column').sortable('destroy');
			$('.row').sortable('destroy');
			$('.top-modules-list').sortable('destroy');
			 
			 
			$('.edit,.column').sortable({
   // items: '.row:not(.disabled),.col',
	 items: '.col,li.module-item',
	 dropOnEmpty:true,
	//   forcePlaceholderSize: true,
	//    forceHelperSize : true,
	    greedy: true,
	   tolerance: 'pointer',
	
	 handle: '.mw-sorthandle-col',
	   revert: true,
	   //  helper: 'clone',
	placeholder: "ui-state-highlight",
	// connectWith: '.edit,.row>.column',
	 connectWith: '.row>.column',
	start: function( event, ui ) {
		//var place2 = $('<div class="empty ui-state-highlight"><span>Please drag items here</span></div>');
			 window.mw_drag_started = true;
			$(ui.placeholder).find(".col>*[contenteditable=true])").each(function(c) {
                $(this).freshereditor("edit", false);
            });
			
			
			 $( '.mw-sorthandle', '.column' ).remove();
			
			   $('.row').each(function(index) {
			 	 $(this).equalHeights() ;
				});
				
			
				
				$(".column").each(function (c) {
                if ($("div", this).size() == 0) {
                   // $(this).html(window.mw_empty_column_placeholder);
				//	 $(this).html('aaaaa');
                } else {
					//$(this).append(place2);
					//$('.empty').fadeIn('fast') 
				}
				//$(this).find('.empty').fadeIn('fast')
				
					 
            })
			  $( this ).sortable( 'refreshPositions' )
  
  },
      sort: function(e,ui){
		   window.mw_drag_started = true;
		//  $('.empty').hide()
    $(ui.placeholder).closest('.empty:first').fadeIn('fast')
	 // $(ui.placeholder).closest('.ui-state-highlight:first').css({"width":"100%", "height" : ui.item.height()});
	  //$(".ui-state-highlight").css({"width":"100%", "height" : ui.item.height()});
	 //  $(ui.placeholder).css({"height" : ui.item.height()});
	   
	
	   
	   
	//$(".ui-state-highlight").css({"width":"100%", "height" : "100%"});
	//$(".ui-state-highlight").css({"width":"100%", "height" :ui.placeholder.parent('.column').height()});
	
	
	
	
  $(ui.placeholder).find('.column').html(Number($(".col:visible").index(ui.placeholder)+1));
ui.helper.width(ui.item.width());

if(ui.helper.height() > 100){
//ui.helper.height(ui.placeholder.height());
} else {
ui.helper.height(ui.item.height());	
}



 // $(ui.placeholder).css({"height" : ui.helper.innerHeight()});
    $(ui.placeholder).css({"height" : ui.item.height()});
	
	
	
	
	
	
              },
			  
	stop: function(event, ui) {
		 window.mw_drag_started = false;
           $('.empty').fadeOut('fast').remove();
		     $('.ui-state-highlight').remove();
			mw_load_new_dropped_modules();
						$('.row').each(function(index) {
							$(this).equalHeights() ;
					});
	       },
	 over: function(event, ui) {
		 $(this).find('.empty').fadeIn('fast')
		  }	,
		  
		  	 out: function(event, ui) {
				 $(this).css('min-height', '10px');
	 	//$(ui.sender).find('.empty').fadeOut('fast')
		//$(ui.sender).find('.ui-state-highlight').fadeOut('fast')
		  }	,
		
	activate: function(en, ui) {
		$(".column").each(function (c) {
                if ($("div", this).size() == 0) {
                     $(this).html(window.mw_empty_column_placeholder);
				//	 $(this).html('aaaaa');
                } else {
					//$(this).append(place2);
					//$('.empty').fadeIn('fast')
				}
				
            })
				$('.row').each(function(index) {
							$(this).equalHeights() ;
					});
					 $( this ).sortable( 'refreshPositions' )
      //  $(this).css('min-height',  ui.item.height());
		//$(this).find('.empty').fadeIn('fast')
		
    },
    deactivate: function(en, ui) {
		 window.mw_drag_started = false;
        $(this).css('min-height', '10px');
    }
		
		
		
});
			
			
			
			
			
			$('.top-modules-list').sortable({
    items: '.module_draggable',
	stop: function(event, ui) {
           $('.empty').fadeOut('fast') //.remove();
			mw_load_new_dropped_modules();
					 
	       },
			connectWith: '.edit,.column,.row>.column'
			});
			
			 
			$('.edit').sortable({
    items: '.row',
  handle: '.mw-sorthandle-row',
			connectWith: '.edit'
			});	
			
			
			
			
			
			
			
			
			
			
			/*
			
			
			
			
			
			
			
			
			
			
					$('.coxxZxZxlumn:not(.disabled), .top-modxzxules-list').sortable({
    items: '.cosdfsdl,.module_draggable',
	dropOnEmpty:true,

   revert: true,
	 // tolerance: 'pointer',
	      forceHelperSize: true,
	  placeholder: "ui-state-highlight",
   //forcePlaceholderSize: true,
	 connectWith: '.column, .edit>' ,
	   // helper: 'clone',
	   change: function(event, ui) { 
	  
	   },
	   over: function(event, ui){
		  // if($.trim(ui.placeholder.html()) == '') ui.placeholder.html(place2);;

    
	   }
,
	    start: function( event, ui ) {
			 $( '.mw-sorthandle', '.column' ).remove();
			 $(".ui-state-highlight").remove();
			 $(".empty").remove();
			$(".column", '.row').each(function (c) {
                if ($("div", this).size() == 0) {
                    $(this).html(place2);
                }
            })
			   $('.row').each(function(index) {
        $(this).equalHeights() ;
});
	
			 // $( this ).sortable( 'refreshPositions' )
  
  },

      sort: function(e,ui){
		   $('.empty').remove();
   // $(".ui-state-highlight").css({"width":"100%", "height" : ui.item.height()});
	$(ui.placeholder).css({"height" : ui.item.height()});
 // $(ui.placeholder).html(Number($(".col:visible").index(ui.placeholder)+1));
ui.helper.width(ui.placeholder.width());
//ui.helper.height(ui.item.height());
              },
			  
			  remove: function(event, ui) {
            $('.empty').remove();
        },
		 change: function(event, ui) {
   $( this ).sortable( 'refreshPositions' )
   
    },
	  receive: function(event, ui) {
            $('.empty').remove();
			
			
			
			//mw_load_new_dropped_modules();
			
			
			
			
        },
		  activate: function(en, ui) {
        $(ui.placeholder).css('min-height',  ui.item.height());
    },
    deactivate: function(en, ui) {
         $(this).css('min-height', '10px');
		 $('.empty').remove();
    },
		
		stop: function(event, ui) {
            $('.empty').remove();
			
			
			
			mw_load_new_dropped_modules();
			
			
			
			
			
			
			
			
		//	 $(this).equalHeights() ;
		  
    $('.row').each(function(index) {
        $(this).equalHeights() ;
});
				// $( ui.placeholder.parents('.row')).addclass('aaaaaaaaa');

			
        }

	 
});
				*/	
				
				
				
				
				
					
		 
		 
			 $(".module-item").disableSelection();
			
			 $(".mw-sorthandle", '.edit').disableSelection();
			
			
			
			//$(".col>*:not(.mw-sorthandle)").freshereditor("edit", true);
			
			 $(".col>*:not(.mw-sorthandle)").die('click');
			 $(".col>*:not(.mw-sorthandle)").die('blur');
			
			 $(".col>*:not(.mw-sorthandle,[contenteditable=false])").click(function() {
                 $(this).freshereditor("edit", true);
				 $(this).freshereditor("edit", true);
				 $(this).focus()
            });
           $(".col>*:not(.mw-sorthandle,[contenteditable=false])").blur(function() {
              //  $(this).freshereditor("edit", false);
            });
			
			
			
			
			$(".row,.col").die('mousedown');
			$(".col").live(
        'mousedown',
        function (e) {

$el_id = $(this).attr('id');
if($el_id == undefined || $el_id == 'undefined' ){
$el_id = 'mw-element-'+new Date().getTime()+Math.floor(Math.random()*11);
  $(this).attr('id', $el_id);
}
window.mw_element_id = $el_id;
mw_make_css_editor($el_id)
e.stopPropagation();
        });
			
			
				$(".row").live(
        'click',
        function (e) {

$el_id = $(this).attr('id');
if($el_id == undefined || $el_id == 'undefined' ){
$el_id = 'mw-row-'+new Date().getTime()+Math.floor(Math.random()*11);
  $(this).attr('id', $el_id);
}
window.mw_row_id = $el_id;
mw_make_row_editor($el_id)





	 $exisintg_num = $('#'+$el_id).children(".column").size();



if($exisintg_num > 0){


 $('#'+$el_id).children(".column").each(function() {
       
	   
	    $el_id_column = $(this).attr('id');
if($el_id_column == undefined || $el_id_column == 'undefined' ){
$el_id_column = 'mw-column-'+new Date().getTime()+Math.floor(Math.random()*11);
  $(this).attr('id', $el_id_column);
}
	   
   });

		
				

}











//e.stopPropagation();
        });	
			
			
		 
			 
			 $(".row", '.edit').mouseenter(function(){
   $has = $(this).children(":first").hasClass("mw-sorthandle");
   if($has == false){
	$(this).prepend("<div class='mw-sorthandle mw-sorthandle-row'><span>&nbsp;</span>");
}

 $(this).children(".mw-sorthandle-row:first").show();

if( window.mw_drag_started == false){
	
	 $(this).children(".column").each(function(index) {
	 $el_id_column = $(this).attr('id');
if($el_id_column == undefined || $el_id_column == 'undefined' ){
$el_id_column = 'mw-column-'+new Date().getTime()+Math.floor(Math.random()*11);
  $(this).attr('id', $el_id_column);
}
	   
   });
	
	
	
	
	   $(this).children(".column").each(function(index) {
		   
		   
		   
		   $id_col = $(this).next('.column').attr('id');
if($id_col == undefined){
	 $id_col = $(this).prev('.column').attr('id');
	 if (window.console != undefined) {
							 console.log('prev ' + $id_col );	
						}
}
 
		  
		  
		   
		   
					$(this).resizable({
				  handles: 'e' ,
					containment:  $(this).parent('.row'),
				 	alsoResizeReverse: '#'+$id_col,
				   resize: function(event, ui) { 
				     $(this).parent(".row").equalHeights() ; 
				 //   $(ui.helper).closest('.column').css("width","-"+ui.size.width+"px");
				 
				 // $prev_or_next = ui.helper.next('.column');
				//$prev_or_next = ui.helper.siblings('.column:first')
				//$prev_or_next = ui.helper.closest('.column:first');
				 $id_colx = $(this).attr('id');
				 if (window.console != undefined) {
						console.log('resizing ' +  $id_colx +$id_col);	
						}
						
						
				 
				// $prev_or_next.css("width","-"+ui.size.width+"px");
					if (window.console != undefined) {
							//console.log('resizing ' + ui.helper.html(Math.random()) );	
						}
					 //$(this).parent(".row").height('auto') ;
				  // $(this).parent(".row").equalHeights() ;
				   
					},
				   
				   
				  stop: function(event, ui) { 
				   $(ui.element).height('auto');
				   $(this).parent(".row").equalHeights() ; 
				   
				   
			 
				   }
				  
				 // alsoResizeReverse: $(this).closest(".column")
							 //   alsoResize:$(this).find('img')
							});   ;
});
	
	
	
	
	
	
	
	
	
	
	
	
	
	
 






}







}).mouseleave(function(){
if( window.mw_drag_started == false){
 $(this).children(".column").resizable('destroy');
}
// $(this).children(".mw-sorthandle-row:first").hide();

})  
			 
			 
			 
			 		 $(".col", '.edit').mouseenter(function(){
   $has = $(this).children(":first").hasClass("mw-sorthandle");
   if($has == false){
	$(this).prepend("<div class='mw-sorthandle mw-sorthandle-col'><span>&nbsp;</span>");
}







})
			 

			
			
			   $("#mw-layout-edit-site-top-bar-r").html("Drag and drop edit");
			  window.mw_sortables_created = true
			 
			  window.mw_sortables_created = true
			 
			  }
			 
		 


}
	
	
	
		       $('.module', '.edit').live('blur', function () {

 });
	
	 $('.module' , '.edit').live('mousenter',function(e) {
					$(this).children('[draggable]').removeAttr('draggable')							  
		});										  
												  
 $('.colasdasdasdumn' , '.rasdasdasow').live('click',function(e) {
	 
	 
	 	    $el_id_column = $(this).attr('id');
if($el_id_column == undefined || $el_id_column == 'undefined' ){
$el_id_column = 'mw-column-'+new Date().getTime()+Math.floor(Math.random()*11);
  $(this).attr('id', $el_id_column);
}
	 $prow = $(this).parent('.row').attr('id');
	$also =  $('#'+$prow).children(".column").not("#"+$el_id_column);
	 
 	 $(this ).resizable( "destroy" )
	 
				$prow = $(this).parent('.row').attr('id');
				//alert($prow );
				$(this ).resizable({
								   handles: 'e',
								// alsoResizeReverse: $(this ).siblings('.column'),
							//	 aspectRatio: true,
								     // alsoResizeReverse: $(this).parent('.row').children(".column").not(this),
  //alsoResizeReverse: $also,
								//  animate: true,
								   resize: function(event, ui) {
										 

										}
								  });
				
				
				
				
										  
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

































/*	
 *	jQuery dotdotdot 1.4.0
 *	
 *	Copyright (c) 2012 Fred Heusschen
 *	www.frebsite.nl
 *
 *	Plugin website:
 *	dotdotdot.frebsite.nl
 *
 *	Dual licensed under the MIT and GPL licenses.
 *	http://en.wikipedia.org/wiki/MIT_License
 *	http://en.wikipedia.org/wiki/GNU_General_Public_License
 */

(function( $ )
{
	if ( $.fn.dotdotdot )
	{
		return;
	}

	$.fn.dotdotdot = function( o )
	{
		if ( this.length == 0 )
		{
			debug( true, 'No element found for "' + this.selector + '".' );
			return this;
		}
		if ( this.length > 1 )
		{
			return this.each(
				function()
				{
					$(this).dotdotdot( o );
				}
			);
		}


		var $dot = this,
			$tt0 = this[ 0 ];

		if ( $dot.data( 'dotdotdot' ) )
		{
			$dot.trigger( 'destroy.dot' );
		}

		$dot.bind_events = function()
		{
			$dot.bind(
				'update.dot',
				function( e, c )
				{
					e.preventDefault();
					e.stopPropagation();

					opts.maxHeight = ( typeof opts.height == 'number' ) 
						? opts.height 
						: getTrueInnerHeight( $dot );

					opts.maxHeight += opts.tolerance;

					if ( typeof c != 'undefined' )
					{
						if ( typeof c == 'string' || c instanceof HTMLElement )
						{
					 		c = $('<div />').append(c).contents();
						}
						if ( c instanceof $ )
						{
							orgContent = c;
						}
					}

					$inr.empty();
					$inr.append( orgContent.clone( true ) );

					var after = false,
						trunc = false;

					if ( conf.afterElement )
					{
						after = conf.afterElement.clone( true );
						conf.afterElement.remove();
					}
					if ( test( $inr, opts ) )
					{
						if ( opts.wrap == 'children' )
						{
							trunc = children( $inr, opts, after );
						}
						else
						{
							trunc = ellipsis( $inr, $inr, opts, after );
						}
					}
					conf.isTruncated = trunc;
					return trunc;
				}
			);
			$dot.bind(
				'isTruncated.dot',
				function( e, fn )
				{
					e.preventDefault();
					e.stopPropagation();

					if ( typeof fn == 'function' )
					{
						fn.call( $tt0, conf.isTruncated );
					}
					return conf.isTruncated;
				}
			);
			$dot.bind(
				'originalContent.dot',
				function( e, fn )
				{
					e.preventDefault();
					e.stopPropagation();

					if ( typeof fn == 'function' )
					{
						fn.call( $tt0, orgContent );
					}
					return orgContent;
				}
			);
			$dot.bind(
				'destroy.dot',
				function( e )
				{
					e.preventDefault();
					e.stopPropagation();

					$dot.unwatch();
					$dot.unbind_events();
					$dot.empty();
					$dot.append( orgContent );
					$dot.data( 'dotdotdot', false );
				}
			);
		};	//	/bind_events

		$dot.unbind_events = function()
		{
			$dot.unbind('.dot');
		};	//	/unbind_events

		$dot.watch = function()
		{
			$dot.unwatch();
			if ( opts.watch == 'window' )
			{
				$(window).bind(
					'resize.dot',
					function()
					{
						if ( watchInt )
						{
							clearInterval( watchInt );
						}
						watchInt = setTimeout(
							function()
							{
								$dot.trigger( 'update.dot' );
							}, 10
						);
					}
				);
			}
			else
			{
				watchOrg = getSizes( $dot );
				watchInt = setInterval(
					function()
					{
						var watchNew = getSizes( $dot );
						if ( watchOrg.width  != watchNew.width ||
							 watchOrg.height != watchNew.height )
						{
							$dot.trigger( 'update.dot' );
							watchOrg = getSizes( $dot );
						}
					}, 100
				);
			}
		};
		$dot.unwatch = function()
		{
			if ( watchInt )
			{
				clearInterval( watchInt );
			}
		};

		var	orgContent	= $dot.contents(),
			opts 		= $.extend( true, {}, $.fn.dotdotdot.defaults, o ),
			conf		= {},
			watchOrg	= {},
			watchInt	= null,
			$inr		= $dot.wrapInner( '<' + opts.wrapper + ' class="dotdotdot" />' ).children();

		conf.afterElement	= getElement( opts.after, $inr );
		conf.isTruncated	= false;

		$inr.css({
			'height'	: 'auto',
			'width'		: 'auto'
		});

		$dot.data( 'dotdotdot', true );
		$dot.bind_events();
		$dot.trigger( 'update.dot' );
		if ( opts.watch )
		{
			$dot.watch();
		}

		return $dot;
	};



	//	public
	$.fn.dotdotdot.defaults = {
		'wrapper'	: 'div',
		'ellipsis'	: '... ',
		'wrap'		: 'word',
		'tolerance'	: 0,
		'after'		: null,
		'height'	: null,
		'watch'		: false,
		'debug'		: false
	};
	

	//	private
	function children($elem, o, after)

	{
		var $elements = $elem.children(),
			isTruncated = false;

		$elem.empty();

		for ( var a = 0, l = $elements.length; a < l; a++ )
		{
			var $e = $elements.eq( a );
			$elem.append( $e );
			if ( after )
			{
				$elem.append( after );
			}
			if ( test( $elem, o ) )
			{
				$e.remove();
				isTruncated = true;
				break;
			}
			else
			{
				if ( after )
				{
					after.remove();
				}
			}
		}
		return isTruncated;
	}
	function ellipsis( $elem, $i, o, after )
	{
		var $elements = $elem.contents(),
			isTruncated = false;

		$elem.empty();

		var notx = 'table, thead, tbody, tfoot, tr, col, colgroup, object, embed, param, ol, ul, dl, select, optgroup, option, textarea, script, style';

		for ( var a = 0, l = $elements.length; a < l; a++ )
		{

			if ( isTruncated )
			{
				break;
			}

			var e	= $elements[ a ],
				$e	= $(e);

			if ( typeof e == 'undefined' )
			{
				continue;
			}

			$elem.append( $e );
			if ( after )
			{
				var func = ( $elem.is( notx ) )
					? 'after'
					: 'append';
				$elem[ func ]( after );
			}
			if ( e.nodeType == 3 )
			{
				if ( test( $i, o ) )
				{
					isTruncated = ellipsisElement( $e, $i, o, after );
				}
			}
			else
			{
				isTruncated = ellipsis( $e, $i, o, after );
			}

			if ( !isTruncated )
			{
				if ( after )
				{
					after.remove();
				}
			}
		}
		return isTruncated;
	}
	function ellipsisElement( $e, $i, o, after )
	{
		var isTruncated	= false,
			e			= $e[ 0 ];

		if ( typeof e == 'undefined' )
		{
			return false;
		}

		var seporator	= ( o.wrap == 'letter' ) ? '' : ' ',
			textArr		= getTextContent( e ).split( seporator );

		setTextContent( e, textArr.join( seporator ) + o.ellipsis );

		for ( var a = textArr.length - 1; a >= 0; a-- )
		{
			if ( test( $i, o ) )
			{
				var end = getTextContent( e ).length - ( textArr[ a ].length + seporator.length + o.ellipsis.length ),
					txt = ( end > 0 ) 
						? getTextContent( e ).substring( 0, end )
						: '';

				setTextContent( e, txt + o.ellipsis );

			}
			else
			{
				isTruncated = true;
				break;
			}
		}

		if ( !isTruncated )
		{
			var $w = $e.parent();
			$e.remove();
			$n = $w.contents().eq( -1 );

			isTruncated = ellipsisElement( $n, $i, o, after );
		}

		return isTruncated;
	}
	function test( $i, o )
	{
		return $i.innerHeight() > o.maxHeight;
	}
	function getSizes( $d )
	{
		return {
			'width'	: $d.innerWidth(),
			'height': $d.innerHeight()
		};
	}
	function setTextContent( e, content )
	{
		if ( e.innerText )
		{
			e.innerText = content;
		}
		else if ( e.nodeValue )
		{
			e.nodeValue = content;
		}
		else if (e.textContent)
		{
			e.textContent = content;
		}
	}
	function getTextContent( e )
	{
		if ( e.innerText )
		{
			return e.innerText;
		}
		else if ( e.nodeValue )
		{
			return e.nodeValue;
		}
		else if ( e.textContent )
		{
			return e.textContent;
		}
		else
		{
			return "";
		}
	}
	function getElement( e, $i )
	{
		if ( typeof e == 'undefined' )
		{
			return false;
		}
		if ( !e )
		{
			return false;
		}
		if ( typeof e == 'string' )
		{
			e = $(e, $i);
			return ( e.length )
				? e 
				: false;
		}
		if ( typeof e == 'object' )
		{
			return ( typeof e.jquery == 'undefined' )
				? false
				: e;
		}
		return false;
	}
	function getTrueInnerHeight( $el )
	{
		var h = $el.innerHeight(),
			a = [ 'paddingTop', 'paddingBottom' ];

		for ( z = 0, l = a.length; z < l; z++ ) {
			var m = parseInt( $el.css( a[ z ] ) );
			if ( isNaN( m ) )
			{
				m = 0;
			}
			h -= m;
		}
		return h;
	}
	function debug( d, m )
	{
		if ( !d )
		{
			return false;
		}
		if ( typeof m == 'string' )
		{
			m = 'dotdotdot: ' + m;
		}
		else
		{
			m = [ 'dotdotdot:', m ];
		}

		if ( window.console && window.console.log )
		{
			window.console.log( m );
		}
		return false;
	}
	
	//	override jQuery.html
	var _orgHtml = $.fn.html;
    $.fn.html = function( str ) {
		if ( typeof str == 'string' && this.data( 'dotdotdot' ) )
		{
			this.trigger( 'update', str );
			return this;
		}
        return _orgHtml.call( this, str );
    };

	//	override jQuery.text
	var _orgText = $.fn.text;
    $.fn.text = function( str ) {
		if ( typeof str == 'string' && this.data( 'dotdotdot' ) )
		{
			var temp = $( '<div />' );
			temp.text( str );
			str = temp.html();
			temp.remove();
			this.trigger( 'update', str );
			return this;
		}
        return _orgText.call( this, str );
    };

})( jQuery );


 
 //jQuery(‘#mycontainer’).equalHeights(300);


 
     $.ui.plugin.add("resizable", "alsoResizeReverse", {

        start: function(event, ui) {

            var self = $(this).data("resizable"), o = self.options;

            var _store = function(exp) {
                $(exp).each(function() {
                    $(this).data("resizable-alsoresize-reverse", {
                        width: parseInt($(this).width(), 10), height: parseInt($(this).height(), 10),
                        left: parseInt($(this).css('left'), 10), top: parseInt($(this).css('top'), 10)
                    });
                });
            };

            if (typeof(o.alsoResizeReverse) == 'object' && !o.alsoResizeReverse.parentNode) {
                if (o.alsoResizeReverse.length) { o.alsoResize = o.alsoResizeReverse[0];    _store(o.alsoResizeReverse); }
                else { $.each(o.alsoResizeReverse, function(exp, c) { _store(exp); }); }
            }else{
                _store(o.alsoResizeReverse);
            }
        },

        resize: function(event, ui){
            var self = $(this).data("resizable"), o = self.options, os = self.originalSize, op = self.originalPosition;

            var delta = {
                height: (self.size.height - os.height) || 0, width: (self.size.width - os.width) || 0,
                top: (self.position.top - op.top) || 0, left: (self.position.left - op.left) || 0
            },

            _alsoResizeReverse = function(exp, c) {
                $(exp).each(function() {
                    var el = $(this), start = $(this).data("resizable-alsoresize-reverse"), style = {}, css = c && c.length ? c : ['width', 'height', 'top', 'left'];

                    $.each(css || ['width', 'height', 'top', 'left'], function(i, prop) {
                        var sum = (start[prop]||0) - (delta[prop]||0);
                        if (sum && sum >= 0)
                            style[prop] = sum || null;
                    });

                    //Opera fixing relative position
                    if (/relative/.test(el.css('position')) && $.browser.opera) {
                        self._revertToRelativePosition = true;
                        el.css({ position: 'absolute', top: 'auto', left: 'auto' });
                    }

                    el.css(style);
                });
            };

            if (typeof(o.alsoResizeReverse) == 'object' && !o.alsoResizeReverse.nodeType) {
                $.each(o.alsoResizeReverse, function(exp, c) { _alsoResizeReverse(exp, c); });
            }else{
                _alsoResizeReverse(o.alsoResizeReverse);
            }
        },

        stop: function(event, ui){
            var self = $(this).data("resizable");

            //Opera fixing relative position
            if (self._revertToRelativePosition && $.browser.opera) {
                self._revertToRelativePosition = false;
                el.css({ position: 'relative' });
            }

            $(this).removeData("resizable-alsoresize-reverse");
        }
    });


