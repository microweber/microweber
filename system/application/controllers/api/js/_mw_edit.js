if (window.console != undefined) {
	console.log('Microweber Javascript Edit Page Framework Loaded');
}

 
window.onscroll = function () {
    //alert("scrolling");
	 $is_dragged = window.mw_dragging;
	 if($is_dragged == undefined || $is_dragged == false  ){
		 $('#module_temp_holder').hide();
	 }
}
 

$.dataFind = function(data, findwhat){
       var div = document.createElement('div');
       div.innerHTML = data;
       div.className = 'xhidden';
       document.body.appendChild(div);
       setTimeout(function(){$(div).destroy()}, 5);
       return $(div).find(findwhat)
    }



function mw_insertTextAtCursor(text) {
    var sel, range, html;
    if (window.getSelection) {
        sel = window.getSelection();
        if (sel.getRangeAt && sel.rangeCount) {
            range = sel.getRangeAt(0);
            range.insertNode( document.createTextNode(text) );
        }
    } else if (document.selection && document.selection.createRange) {
        range = document.selection.createRange();
        range.pasteHTML(text);
    }
}
// here

function mw_insert_module_at_cursor($module, $full_tag){
	
	 $mod_id = "module_"+Math.floor(Math.random()*9999)+Math.floor(Math.random()*9999);

	 
	 
	 
     mw1 =unescape($module);
 
 
		if(mw1 == undefined){
		
		} else {
			if($full_tag == undefined){
		mw1 = "<div><microweber module_id='" + $mod_id + "'  module='" + mw1 + "' /></div><div><br/></div>";       
			} else {
				mw1 =unescape($full_tag);
				//mw1 =$full_tag;       
			}
		      
		  //  $(".edit").addClass("mw_edited");
		     
		      
		     
		     var range = rangy.getSelection();
		     range= range.rangeCount ? range.getRangeAt(0) : null;
		     if (range != null && range != '') {
		
		    	// alert(range);
		     	  var newdiv = document.createElement('div');
		
		
		
		     
		
		     	  newdiv.innerHTML =mw1;
		
		
		
		         range.insertNode(newdiv);
		         rangy.getSelection().setSingleRange(range);
		        
		         mw.saveALL();
		     } else {
		    	 
		    	 if ( $.browser.msie ) {
		    		 $(".to_here_click:first").prepend(mw1); 
		    		 } else {
		    		//	 alert(mw1);
				    	 mw_insertHtmlAtCursor(mw1);
				    	 
				    	 
				    	 
		    		 }
		    	 
		    	 
		    	 
		    	
		    	
		    	 mw.saveALL();
		    	 
		    	 
		     
		     }
		}
}

function mw_make_editables(){
	 $('.edit').attr('contentEditable', true);	
}

function mw_remove_editables(){
/*
 * try { $('.edit').removeAttr('contentEditable'); } catch (e) {
 * $('.edit').attr('contentEditable', false); }
 */
	$('.edit').attr('contentEditable', false);
}

function mw_make_draggables(){
	
 
	
	
	
	
	$('#module_temp_holder').unbind('click', function() {
		 
		});
	
	  $("#module_temp_holder").bind("click", function () {
		    
		   
		 $is_dragged = window.mw_dragging;
    	 if($is_dragged == undefined || $is_dragged == false  ){
     
    		var $module_by_name  =$('#module_temp_holder_id').val();   
    		//alert($module_by_name);
    		load_edit_module_by_module_id($module_by_name)
    
    		
    		
    		
    	 } else {
    		  
    	
    	 }
    	 
	});
	  
 
	  
	  
 
      
      
	
/*	  $('.edit').delegate('*', 'hover', function() { 
		  
		  $(this).addClass('to_here');   
		  //$(this).removeClass('to_here_drop');;   
		 //$('.to_here_drop').removeClass('to_here_drop');
		  $('.to_here_drop').removeClass('to_here_drop');
		  
	    	 
		});*/
	  
/* $('.edit > *').live('mouseover mouseout', function(event) {
	 $is_dragged = window.mw_dragging;
		  if (event.type == 'mouseover') {
			  $(this).addClass('to_here');   
			  if($is_dragged == undefined || $is_dragged == false  ){
				
				  //$(this).removeClass('to_here_drop');;   
				 //$('.to_here_drop').removeClass('to_here_drop');
				  //$('.to_here_drop').parents().removeClass('to_here_drop');
			 } else {
				// $('.to_here_drop').parents().removeClass('to_here_drop');
				 
				  $(this).addClass('to_here_drop');   
				 
			 }
		  } else {
			  $(this).removeClass('to_here');   
			  if($is_dragged == undefined || $is_dragged == false  ){
				  
				  $('.to_here_drop').removeClass('to_here_drop');;   
			  }
		    // do something on mouseout
		  }
		});*/
	  
	  
	  
/* $('.edit > *').live('hover', function() { 
		  
		  
		  $is_dragged = window.mw_dragging;
			 if($is_dragged == undefined || $is_dragged == false  ){
				  $(this).addClass('to_here');   
				  //$(this).removeClass('to_here_drop');;   
				 //$('.to_here_drop').removeClass('to_here_drop');
				  //$('.to_here_drop').parents().removeClass('to_here_drop');
			 } else {
				// $('.to_here_drop').parents().removeClass('to_here_drop');
				  $(this).addClass('to_here_drop');   
				 
			 }
			 
			 
			 
		
		  
	    	 
		});*/
	
	 
	
	  //$("#module_temp_holder").die("tripleclick");
	  /*
	  $("#module_temp_holder").keypress(function(e) {
	      switch(e.keyCode) { 
	         // User pressed "up" arrow
	         case 38:
	            navigate('up');
	         break;
	         // User pressed "down" arrow
	         case 40:
	            navigate('down');
	         break;
	         // User pressed "enter"
	         case 13:
	        	 var $module_by_id  =   $('#module_temp_holder_id').val();
			        //alert($module_by_id); 
			         $module_by_id  =$("div[module_id='"+$module_by_id+"']"); 
			        $module_by_id.remove();
	         break;
	      }
	   });
	  */
	  
	  
	/*  $("#module_temp_holder").live('click', function() { 
		        
	 
		  var $module_by_id  =   $('#module_temp_holder_id').val();
		        //alert($module_by_id); 
		         $module_by_id  =$("div[module_id='"+$module_by_id+"']"); 
		        $module_by_id.remove();
		});*/

	
	
	  
	  $(".edit").undelegate("div.module", "mouseover");
	    
	  $('.edit').delegate('div.module', 'mouseover', function() { 
	  
	//$('.edit').delegate('.module', 'hover', function() { 
	 // $(".module").live("hover",function(){
		  if ($('.mw_live_edit_on_off').hasClass('mw_live_edit_on_off_state_off')) {
			  $('#module_temp_holder').hide();
			  return true;
		  }
		  
		  
		  $class1 = $(this).hasClass('mw_no_module_mask');
		 if( $class1 == true){
			  $('#module_temp_holder').hide();
			  
		  } else {
		  
		 $is_dragged = window.mw_dragging;
    	 if($is_dragged == undefined || $is_dragged == false  ){
    	 $val =  $(this).attr('mw_params_module');    
    		var $module_by_id  =$(this).attr('module_id');   
    		//load_edit_module_by_module_id($module_by_id)
    	 $('#module_temp_holder_value').html($val);
    	 $('#module_temp_holder_id').val($module_by_id);
    	 
    	 if($("#mw_module_param_to_copy").length == 0){
         	
         } else {
        	   // $original_attrs =  $(this).getAttributes();
            	
           	 
        	    //  $('#mw_module_param_to_copy').val($original_attrs);
         }

    	 
    	 
    	
    	
    	 
    	 var target = $(this);
         var dialog = $('#module_temp_holder');
         $('#module_temp_holder').show();
       //  var half_width = ($(this).css('width').slice(0, -2))/2;
         //var half_height = ($(this).css('height').slice(0, -2))*0.3;
          var dialog_width = $(this).width();
         var dialog_height = $(this).height();
   
       //  dialog.positionOn(target, 'center');
         dialog.show();
         var offset = $(this).offset();
         
         var centeryk=screen.availHeight*0.4;
         var centerxk=screen.availWidth*0.4;
         
         
       //  this.css("top", ( $(window).height() - this.height() ) / 2+$(window).scrollTop() + "px");
   //      this.css("left", ( $(window).width() - this.width() ) / 2+$(window).scrollLeft() + "px");
       
         if($val == 'content/text'){
        	 var dialog_width = '20';
             var dialog_height = '20';
         }
         
         
         
      /*   dialog.css({
        	 position: 'absolute',
  	       zIndex:   5000,
  	       top:      offset.top - $(window).scrollTop(), 
  	       left:     offset.left - $(window).scrollLeft() ,
        	    'width': dialog_width,
        	    'height': dialog_height  
        	    //'position': 'absolute',
        	    
        	    
        	});*/
         
         
         dialog.css({
        	 position: 'absolute',
  	       zIndex:   5000,
  	       top:      offset.top - $(window).scrollTop(), 
  	       left:     offset.left - $(window).scrollLeft() ,
        	    'width': dialog_width,
        	    'height': dialog_height  
        	    //'position': 'absolute',
        	    
        	    
        	});
         
         
         
         
         
    	 
    	  
    	 
    	 
    	 
    	 }
	
		  }
	
	
	});

 
	 // $(".module").die("hover");
 
	
	
	 //$(".module").draggable( "destroy" );
  //   $(".module").not(".edit").attr('contentEditable', false);
	 $("div.module").disableSelection();
	
	
	  
	  
	  
	
}

function init_edits(){
	
//	mw_make_draggables();

	
	
	
	// document.execCommand('insertbronreturn', false, true);
	// document.execCommand("insertBrOnReturn", false, false);
	// ------------ http://dev.ckeditor.com/changeset/1176
    var cssApplier;

 rangy.init();
 cssApplier = rangy.createCssClassApplier("to_here", true); // true turns
	// on normalization
  
  
  // Enable multiple selections in IE
  try {
// .. document.execCommand("MultipleSelection", true, true);
  } catch (ex) {}
  
 
  
  // $(".module").not(".edit").disableSelection();
  
 // $(".module > *").disableSelection();
  
  
  
  // $("body").attr('contentEditable', true);
    // $(".module").attr('contentEditable', false);
  // $(".module").not(".edit").attr('contentEditable', false);
// $("body *:not(.edit)")

 // $("*").not(".edit").attr('contentEditable', false);
    
  // / $('body > *:not(.edit)').attr('contentEditable', false);
    
/*  $('.edit').die("mousedown");
    $(".edit").live("mousedown",function(){
    	   if ($('.mw_live_edit_on_off').hasClass('mw_live_edit_on_off_state_on')) {
           $(this).attr('contentEditable',true);
       // $(".edit * ").attr('contentEditable','inherit');
        $("#mw_toolbar:hidden").slideDown();
    	   } else {
    		   mw_remove_editables();
    		  // $(this).attr('contentEditable',false);
    	       // $(".edit * ").attr('contentEditable','inherit');
    	        $("#mw_toolbar").slideUp();
    	   }
        
        
        
        
    // $(this).addClass('mw_edited');
 });
    */
 
    // $(".edit").live("blur",function(){
    	  
    	// $some = $(this).html();
    	
 
    	  	
    // mw.saveALL();
  // $(".to_here").removeClass("to_here");
    // $(".mw_edited").removeClass('mw_edited');
    	
 // })

/*
 * $(".edit").live("blur",function(){ // $(this).attr('contentEditable',false); //
 * $("#mw_toolbar:visible").slideUp(); })
 */

  // $("body").append('init_edits');
    $(".edit").bind( "blur", function(event){
    	// mw.saveALL();
    });
    // Hide the modal dialog when someone clicks outside of it.
$(".module").bind( "focusoutside", function(event){
 // $("#mw_edit_module_iframe").hide();
  $("#module_bar_resp").show();
 // mw_sidebar_nav('#mw_sidebar_modules_holder');
});
    

 
				
 
 	 
 	 
 	 
    
    $(".edit").bind("click", function () {
        $is_dragged = window.mw_dragging;
   	 if($is_dragged == undefined || $is_dragged == false  ){
		$('#module_temp_holder').hide();
   	 }
    	
   	 $('#module_focus_holder_id').val('');
   	 
   	 mw_make_editables();
    	 mw_make_draggables()
    	 mw_sidebar_nav('#mw_sidebar_modules_holder');
    	
    	
        $(this).addClass("mw_edited");


        var id = $(this).attr("id");
        var rel = $(this).attr("rel");


        var is_post = $(this).attr("post");

        var field = $(this).attr("field");
        if (field == undefined) {
            field = '';
        }


        if ($(this).hasClass('editblock')) {
            $tag = 'editblock'
        }

        if ($(this).hasClass('edit')) {
            $tag = 'edit'
        }

        if ($tag == undefined) {
            $tag = '';
        }

        if (load_editblock_history != undefined) {
            $page_json = get_page_json();
            if (is_post != undefined) {
                $id123 = is_post;
                rel = 'post';
            } else {
                $id123 = $page_json.page.id;
            }

            // load_editblock_history(id, rel,id123 ,$tag, field) ;
        }
    });


    $(".edit").bind("click", function () {
        $(this).addClass("mw_edited");
        $(".mw_outline").remove();
        $(".edit *").removeClass("to_here");
        $(".edit *").removeClass("to_here_click");

        var id = $(this).attr("id");
        var rel = $(this).attr("rel");
        var is_post = $(this).attr("post");

        var field = $(this).attr("field");
        if (field == undefined) {
            field = '';
        }

        if ($(this).hasClass('editblock')) {
            $tag = 'editblock'
        }

        if ($(this).hasClass('edit')) {
            $tag = 'edit'
        }


        if ($tag == undefined) {
            $tag = '';
        }

        if (load_editblock_history != undefined) {
            $page_json = get_page_json();

            $page_json = get_page_json();
            if (is_post != undefined) {
                $id123 = is_post;
                rel = 'post';
            } else {
                $id123 = $page_json.page.id;
            }

            // load_editblock_history(id, rel, $id123,$tag, field) ;
        }


    });

    var editblock_ids = "";

    var editblocklength = $(".editblock").length - 1;
    $(".editblock").each(function (i) {
        var id = $(this).attr("id");

        if (editblock_ids == "") {
            editblock_ids = "#" + id;
        }



        if (editblock_ids != "") {
            editblock_ids = editblock_ids + ", #" + id;
        }


    });





    $(".editblock").each(function () {
        $(this).sortable({
            connectWith: editblock_ids,
            items: ".module",
            cancel: ".module .module",
            handle: ".module_edit_bar_handle",
            receive: function (event, ui) {


                var mw1 = ui.item.find("textarea").val();
                mw1 = "<microweber module='" + mw1 + "' />";

                // alert(mw1);

                $(".bar_module").not("#module_bar .bar_module").replaceWith(mw1);

                $(".editblock").each(function () {
                    var id = $(this).attr("id");
                    // save_editblock(id);
                })

                mw.saveALL();

            }
            // handle:".mwedit"
        })
        // $(this).disableSelection();
    });
    
    /*
 $(".edit").undelegate("*", "mouseover");
    
  $('.edit').delegate('*', 'mouseover', function() { 
   // $('.edit *').live('mouseover', function() { 
		  
    	  $is_dragged = window.mw_dragging;
		  if($is_dragged == true){
			  start =  window.mw_last_hover;
			  if(start == undefined){
				  var start = new Date().getTime();
				  window.mw_last_hover = start;
			  }
			  
			  window.mw_last_hover++;
			  
			 $is_in_edit =  $(this).parents('.edit:first').length;
			  if($is_in_edit > 0){
				  window.mw_last_hover++;
			  $(this).attr('mw_last_hover',window.mw_last_hover );
			  $(this).prev().attr('mw_last_hover',window.mw_last_hover - 100);
			  $(this).next().attr('mw_last_hover',window.mw_last_hover - 1000);
			  $(".to_here_drop").attr('mw_last_hover',window.mw_last_hover - 1000);
			  
  
			  window.mw_last_hover++;
			  
			  }
			  
			  
			  
			//   $(".to_here_drop").removeClass("to_here_drop");
			//    $(this).addClass("to_here_drop");
	 
		  } else {
		//	  $(".to_here_drop").removeClass("to_here_drop");
		  }
		  
	    	 
		});*/
    
    
  //  $(".edit > p, .edit > div, .edit > span, .edit > br, .edit *").die("mouseover");
   // $(".edit > p, .edit > div, .edit > span, .edit > br, .edit *").die("mouseover");
   // $(".edit > p, .edit > div, .edit > span, .edit > br, .edit *").live('mouseover', function(event) {
//    $(".edit *").die("mouseover");
//    $(".edit *").live('mouseover', function(event) {
//    	  
//    	//   $(".to_here").removeClass("to_here");
//		  //  $(this).addClass("to_here");
//		    
//		    
//		    
//		//	  $(this).removeClass('to_here');   
//		    $is_dragged = window.mw_dragging;
//			  if($is_dragged == true){
//				  start =  window.mw_last_hover;
//				  if(start == undefined){
//					  var start = new Date().getTime();
//					  window.mw_last_hover = start;
//				  }
//				  
//				  
//				  $(this).attr('mw_last_hover',start );
//				  $(this).prev().attr('mw_last_hover',start - 100);
//				  
//				  
//				 
//				  
//				 
//				  
//				  
//				  window.mw_last_hover++;
//				  
//				  
//				//   $(".to_here_drop").removeClass("to_here_drop");
//				//    $(this).addClass("to_here_drop");
//		 
//			  } else {
//			//	  $(".to_here_drop").removeClass("to_here_drop");
//			  }
//		 
//	  
//	});
    $(".edit > p, .edit > div, .edit > span, .edit > br, .edit *").die("click");
    $(".edit > p, .edit > div, .edit > span, .edit > br, .edit *").live('click', function(event) {
  	  
  	  $(".to_here_click").removeClass("to_here_click");
		    $(this).addClass("to_here_click");
		    
		 tag = ($(this).get(0).tagName);
		 
		 if(tag == "IMG"){
		$check =   $(this).attr("mw_tag_edit");
		if($check == undefined || $check == ''){
			$check =  new Date().getTime()+(Math.floor(Math.random()));
			$(this).attr("mw_tag_edit",$check );
		}
		event.stopPropagation();
		    mw_html_tag_editor($check);
		 }
		    
	  
	});
    
 
	start =  window.mw_last_hover;
	  if(start == undefined){
		  var start = new Date().getTime();
		  window.mw_last_hover = start;
		  start++;
	  }
	  
	  

$('div.edit').each(function (index, domEle) {
		  
			$is_divs = $(domEle).find('div').length
			if($is_divs == 0){
				//$('<div mw_last_hover="'+start+'"><br /></div>').appendTo($(domEle));
				$(domEle).append('<div mw_last_hover="'+start+'"><br /> </div>');
	 
			}
	  	});


    
    $(".edit br, .edit > p, .edit > div, .edit *").droppable({
    	// greedy: true,
		// activeClass: "to_here_drop",
		 hoverClass: "to_here_drop",
    	// accept: ".module",
		 
		 addClasses: false,
	   tolerance: 'pointer', 
	   over: function(ev, ui) {
    	 start =  window.mw_last_hover;
		  if(start == undefined){
			  var start = new Date().getTime();
			  window.mw_last_hover = start;
		  }
		  
		  
		  
		   $(this).attr( 'mw_last_hover' , start);
		   window.mw_last_hover++;
		 },
		 
	//	 tolerance: 'touch',
        drop: function (event, ui) {
    	//   $(".mw_edited").removeClass("mw_edited");
    	 $(this).parents(".edit:first").addClass("mw_edited");
    	
    	  $(".mw_outline").hide();
            $(".module_edit_bar").hide();
           // $( ".ui-droppable" ).removeClass( "ui-droppable");
         // $( ".ui-draggable" ).removeClass( "ui-draggable");
            
            // cssApplier.toggleSelection();
            $mod_id = "module_"+Math.floor(Math.random()*9999)+Math.floor(Math.random()*9999);

            mw1 = ui.draggable.find("textarea").val();
            mw1_force = false;
            $end_me = 0; 
            if($("#module_temp_holder_id").length == 0){
            	
            } else {
             //mw_module_param_to_copy = $("#mw_module_param_to_copy").val();
           // alert(mw_module_param_to_copy);
            	
             	$test_copy = $("#module_temp_holder_id").val();
            	if($test_copy != ''){
            	//	alert($test_copy);
            		var $module_by_id  =$("div[module_id='"+$test_copy+"']"); 
            		var mw1  =$("div[module_id='"+$test_copy+"']").attr('mw_params_module'); 
            		var mw1_mw_params_module  =$("div[module_id='"+$test_copy+"']").attr('mw_params_module'); 
            		var mw1_mw_edit  =$("div[module_id='"+$test_copy+"']").attr('edit'); 
            		var mw1_mw_mw_params_encoded  =$("div[module_id='"+$test_copy+"']").attr('mw_params_encoded'); 
            		
            		 
            		
            		
            		
            		var $mod_id  =$test_copy; 
            		 
            		
            		$module_by_id.remove();
    				mw1_force = "<div><microweber module_id='" + $mod_id + "'  module='" + mw1 + "'   edit='" + mw1_mw_edit + "'  mw_params_module='" + mw1_mw_params_module + "'   /></div><div><br/></div>";  

			            	 $end_me = 0;
            	}
            }
         
            
            
            
            if($end_me == 0){
            
            
            
            
			            
			            
			            
			            module_val = mw1;
			            
			if(mw1 == undefined){
			 
			} else {
				//mw1 = "<div><microweber module_id='" + $mod_id + "'  module='" + mw1 + "' /></div>"; 
				mw1 = "<div><microweber module_id='" + $mod_id + "'  module='" + mw1 + "' /></div><div><br/></div>";  
			}
			if(mw1_force != false){
				mw1 =	mw1_force;
			}
			
			
			
			
			             
			            ui.draggable.parents(".edit:first").addClass("mw_edited");
			           // $(this).addClass("to_here");
			          //  $(this).addClass("mw_edited");
			           
			            if(mw1 == undefined){
			            	mw1 = ui.draggable.html();
			            } else {
			            	
			            }
			          
			            
			            if(mw1 != undefined){
			          
			       // insertTextAtCursor(mw1);
			            }
			         
			            
			             
			         
			            	 // alert(mw1);
			              // alert(range);
			            	if ($(".to_here:first").length) {
			            		
			            	} else {
			            		 /*  $(this).addClass("to_here");
			            		  // $(this).closest("br").addClass("to_here");
			            		   $(this).parents().andSelf().nextAll('br:first').addClass("to_here");;*/
			            	}
			            	
			            	   // $(this).addClass('to_here');
			            	    
			            	   // $('li.current_sub').prevAll("li.par_cat:first");
			            	  // $(this).prevAll("to_here:first").prepend(mw1);
			            	
			            	
			            	// $(".to_here:first").prepend(mw1); 
			            	// $(".to_here:last").prepend(mw1); 
			            //	 $(".to_here:first").prepend(mw1);  
			            	
			            	
			            	
			            	 $theLast_hovered = 0;
				            	
			            	 $('*[mw_last_hover!=""]').each(function(index) {
			            		
			            		$is_last = $(this).attr('mw_last_hover');
			            		$is_last = parseInt($is_last);
			            		
			            		if($is_last > $theLast_hovered){
			            			$theLast_hovered = 	$is_last;
			            		}
			            		
			            		
			            	   // alert(index + ': ' + $(this).text());
			            	    
			            	    
			            	  });
			            	 if($theLast_hovered >  0){
			            	//	 $('*[mw_last_hover="'+$theLast_hovered+'"]:last').prepend(mw1); 
			            		 $('*[mw_last_hover="'+$theLast_hovered+'"]:last').after(mw1); 
			            	 
			            	 }
			            	 
			            	 
			            	 
			            	 
			            	 if($theLast_hovered == 0){
			            	 
			            	
			            	
			            	
			            	to_here_drop_test = $(".to_here_drop").length;
			            	
			  //    alert(to_here_drop_test);
			            	if(to_here_drop_test == 0){
			            		to_here_drop_test2 = $(".to_here").length ;
			            	  alert(to_here_drop_test2);
			            		if(to_here_drop_test2 == 0){
			            			mw_insert_module_at_cursor(escape(mw1), escape(mw1));
			            		} else {
			            			$(".to_here:first").after(mw1); 
			            		}
			            	
			            	
			            		
			            		
			            	} else {
			            		$(".to_here_drop:first").after(mw1);  
			            	}
			            
			            	
			            	// $(".to_here:first").closest("br").append(mw1);
			            	 
			            //	$("body").prepend(  );  
	/*		            	 $theLast_hovered = 0;
			            	
			            	 $('*[mw_last_hover!=""]').each(function(index) {
			            		
			            		$is_last = $(this).attr('mw_last_hover');
			            		$is_last = parseInt($is_last);
			            		
			            		if($is_last > $theLast_hovered){
			            			$theLast_hovered = 	$is_last;
			            		}
			            		
			            		
			            	   // alert(index + ': ' + $(this).text());
			            	    
			            	    
			            	  });*/
			            	 
			            	// alert($theLast_hovered);
			            	 
			            	 }
			       //$('*[mw_last_hover!=""]').text() 
			
			            
			             
			            	   
			            //	 $(this).parents().removeClass("to_here");	   
			            //$(".to_here").removeClass("to_here");
			            $(".to_here_drop").removeClass("to_here_drop");
			           
			        	$('#module_temp_holder').hide();
			            $(".editblock").each(function () {
			                var id = $(this).attr("id");
			                // save_editblock(id);
			                // mw.saveALL();
			            })
           // event.stopPropagation()
            }
            mw.saveALL();
        }
            
    });

    $(document).die("keydown");
    $(document).keydown(function(e) {
    	 if (e.keyCode == 45) { //insert key
			  $is_module_selected =   $('#module_focus_holder_id').val();
			  
			  if($is_module_selected != ''){
				 // $("div[module_id='"+$is_module_selected+"']").parent().prepend('<div>&nbsp;</div><br>');
				  
				  $("div[module_id='"+$is_module_selected+"']").parent().prepend('<div><br></div>');
//alert($is_module_selected);
			  }
			  
			  
		  }
    	 
    	 if (e.keyCode == 46) { //delete key
			  $is_module_selected =   $('#module_focus_holder_id').val();
			  
			  if($is_module_selected != ''){
				mw_delete_module_by_id($is_module_selected)  ;
				 $('#module_focus_holder_id').val('');
			  }
			  
		  }
    	 
    	 
    	 if (e.keyCode == 13) { //enter key
    		 $is_module_selected =   $('#module_focus_holder_id').val();
   		  
   		  if($is_module_selected != ''){
   		/*	 if ( $.browser.msie ) {
   			  
   			 } else {
   			  $("div[module_id='"+$is_module_selected+"']").parent().append('<div> </div><br>');
   			 }*/
   			$("div[module_id='"+$is_module_selected+"']").parent().append('<div><br></div>');
   		  }
			  
		  }
    	 
    	 
    	
    	 
    	 
    	 
});
    
    

    
 
    $('.edit[contenteditable=true]').die("keydown");
		$('.edit[contenteditable=true]').live("keydown", function (e) {
			
			 
			
			$('#module_temp_holder').hide();
			  $(".mw_outline").remove();
	    // trap the return key being pressed
			  
			  
			  
			 
			  
			  
			  
			  
	    if (e.keyCode == 13) {
	    	 $is_module_selected =   $('#module_focus_holder_id').val();
			  
			  if($is_module_selected != ''){
				  $("div[module_id='"+$is_module_selected+"']").parent().append('<div>&nbsp;</div><br>');
			  } else {
	    	 
	    	if(e.shiftKey)	{ 
	    		 document.execCommand('insertHTML', false, '<br /><br />');
	    	} else {
	    		
	    		
	      // insert 2 br tags (if only one br tag is inserted the cursor won't
			// go to the second line)
	     // document.execCommand('insertHTML', false, '<br />');
	      // prevent the default behaviour of return key pressed
	    		
	    		 if ($.browser.webkit) {
	    			  // alert( "this is webkit!" );
	    		
	    				  document.execCommand('insertHTML', false, '<p>&nbsp;</p><br>');
	    			
	    				  
	    				  
	    			
	    			  } else {
	    				 // return true;
	    				 document.execCommand('insertHTML', false, '<p>&nbsp;</p>');
	    				  
	    				/*  try {
	    					  
		    				  } catch (ex) {}*/
	    					 
	    			  }
	    		
	    	
	    	 
	    	 // return false;
	    	}
	    	
			  }
	    	return false;
			  
	    }
	  });
    
		  $(".edit *").die("click");
    $(".edit *").live("click", function (event) {

    // cssApplier.toggleSelection();
        $(".to_here").removeClass("to_here");
        // $(this).attr('contentEditable', true);
        
        

        $(this).parents(".edit:first").addClass("mw_edited");
        $(this).addClass("mw_edited");
        
        $(this).addClass("to_here");
        


    }, function () {

    }

    )
   
     
     
     
     $(".module").die("mouseup");
    $('.module').live('mouseup', function(event) {
    // mw.outline.remove('.module');
    if(window.saving ==false){
     return false;	
    }
    	//event.preventDefault();

  	      var mod_id112= $(this).attr('module_id');    
  	    var mod_name= $(this).attr('mw_params_module');    
  	      
  	      
  	    
  	  //    if(mod_name != 'content/text'){
  	    $(this).disableSelection();
  	   //   } 
  	      
  	      
  	    load_edit_module_by_module_id(mod_id112);
  	  
  	});
    
    
    
   
  
 
    
 }











$(document).ready(function() {
	
	 

 
	if(typeof window.parent.mw_edit_init == 'function'){
	window.parent.mw_edit_init(window.location.href);
	}

	
	$("[module]").each(function(){
		var val = $(this).attr("module");
		var rel = $(this).attr("rel");
		
		 v1 = encodeURIComponent(val);
			   $url = '<? print site_url("api/module") ?>/admin:true/no_config:true/rel:'+rel+'/?module_name:'+v1;
			// alert(v1+$url);
			   // callIframe
		// $(this).append("<input type='button' value='aaa' name='aaa'
		// onclick='javascript:call_edit_module_iframe(\""+$url+"\") ;' />");

			   
 
		
		
		
		
		
		
	});
	
	
	

 $("edit").each(function(){
                 $(this).attr("id", "edit_" + mw.id());
               })


                    init_edits();
                 


 

	


 

$(window).load(function(){
	 // init_edits()


});


       /*
		 * $(".editblock").sortable({ connectWith:".editblock", items:".module",
		 * receive:function(){ $(".editblock").each(function(){ var id =
		 * $(this).attr("id"); save_editblock(id); }) } // handle:".mwedit"
		 * }).disableSelection();
		 */
	}); // end doc ready

var $curent_edit_element_id

function mw_delete_module_by_id($id){
	
	  var $module_by_id  =   $id;
      //alert($module_by_id); 
       $module_by_id  =$("div[module_id='"+$module_by_id+"']"); 
      $module_by_id.remove();
}

function load_edit_module_by_module_id($the_module_id) {
	// mw_remove_editables();
	// mw_make_draggables();
	
	var $module_by_id  =$("div[module_id='"+$the_module_id+"']"); 
	
	
	
	
	

	var edit = $module_by_id.attr("edit");
	 var rel = $module_by_id.attr("rel");
	 
	  
	// if(edit ==undefined){
		  
	// } else {

// if($("#enable_browse").is(":checked")){
     
	   if ($('.mw_live_edit_on_off').hasClass('mw_live_edit_on_off_state_on')) {
		   
		   $('#module_focus_holder_id').val($the_module_id);
	 
		   
		   
	   if (load_editblock_history !=undefined ) {
		  var parent_rel = $module_by_id.parents(".editblock:first").attr("rel");
		  var parent_id = $module_by_id.parents(".editblock:first").attr("id"); 
		   
		   $page_json = get_page_json();
		   
		 // load_editblock_history(parent_id, parent_rel, $page_json.page.id,
			// 'editblock') ;
		   
		   

		   } 
	  

	  // 

	   var id = $module_by_id.attr("module_id");
	   if((id == undefined) || (id == '')){
		   id =     $module_by_id.parents(".module:first").attr("module_id"); 
	//	 
	   }
	   
	// $the_mod_container_id =
	   
	   var no_admin = $module_by_id.attr("no_admin");
	   var module_name = ($module_by_id.attr("mw_params_module"));
	   if(module_name != undefined){
	   module_name =   module_name.replace("/", "_mw_slash_replace_")
	   }
 
		 
		 if (edit ==undefined ) {
		 var edit = $module_by_id.parents(".module:first").attr("edit");
		 }
		 
		// alert(edit);
		 if (edit !=undefined ) {
			 
			
			// .. $("#mw_edit_module_iframe").show();
			// $("#module_bar_resp").hide();
			 
		 	 page_id = $("#mw_edit_page_id").val();
			 post_id = $("#mw_edit_post_id").val();
			 category_id = $("#mw_edit_category_id").val();
		 
			 
	   $url = '<? print site_url("api/module") ?>/admin:true/base64:'+edit+'/page_id:'+page_id+'/post_id:'+post_id+'/category_id:'+category_id+'/'+'module_to_edit:'+module_name+'/';
	  // alert(id);
	   if(id != undefined){
	// alert(id);
		   
		   $(".mw_outline").remove();
		   
		   
	 
			mw.outline.init("div[module_id='"+$the_module_id+"']", '#DCCC01');
		 

			  
		// $("div[module_id='"+$the_module_id+"']").disableSelection();
		   
		   $('#mw_edit_module_iframe_'+id).show();
		   call_edit_module_iframe($url, id);
	   }  
	  


			 }
	   }
	
}


function load_editblock_history($id,$rel, $page_id, $tag, $field) {
	
	 data1 = {}
	   data1.module = 'admin/mics/edit_block_history';
	    data1.id = $id;
	    if($rel != undefined){
	    data1.rel = $rel;
	    }
	    if($page_id != undefined){
	    data1.page_id = $page_id;
	    }
	    
	    if($tag != undefined){
		    data1.tag = $tag;
		    }
	    
	    if($field != undefined){
		    data1.field = $field;
		    }
	    
	   $.ajax({
	  url: '<? print site_url('api/module') ?>',
	   type: "POST",
	      data: data1,

	      async:true,

	  success: function(resp) {

	   $('#mw_block_history').html(resp);

	 

	  }
	    });
		
	
	
	
	
	
}

function load_field_from_history_file($id, $base64fle){


	$.ajax({
		  type: 'POST',
		  url: '<? print site_url("api/content/load_history_file") ?>',
		  data: { history_file: $base64fle },
		  success: function(data) {
			   $("#"+$id).html(data);
		  }
		})
	
	
}

function load_editblock($id, $history_file) {
	$page_json = get_page_json();
	
	$rel = $(".editblock#"+$id).attr('rel');
	
	 if ( typeof $history_file != 'undefined' ) {
		 $history_file = $history_file;
	 } else {
		 $history_file = false; 
	 }
	
	
	$.ajax({
		  type: 'POST',
		  url: '<? print site_url("api/content/load_block") ?>',
		  data: { id: $id, rel:$rel , page_id: $page_json.page.id, history_file:$history_file },
		  success: function(data) {
			  // alert(data);
			   $(".editblock#"+$id).html(data);
			   
			 
			   
			   if ( typeof load_editblock_history != 'undefined' ) {
			   load_editblock_history($id, $rel, $page_json.page.id) ;
			   }
			   
			   
			   
			   
               $(".module").each(function(){
                 $(this).attr("id", "module_id_" + mw.id());
               })
       // init_edits();


		  }
		})
		
		
		
}

function get_page_json() {
	if ( typeof get_page_json.page == 'undefined' ) {
    	$page_data = window.location.href ;
    	$.ajax({
  		  type: 'POST',
  		  url: $page_data,
  		  data: { format: 'json'},
           async:false,
           dataType: "json", 
  		  success: function(data) {
    		get_page_json.page = data;

  		  }
  		})
    }

    
return get_page_json.page;
	
}	
function save_editblock($id) {
	// alert($id);
    $(".module_edit_bar").remove();
	$page_json = get_page_json();
	// alert($page_json);




	

	
	var clone =  $(".editblock#"+$id).find(".mw_save").clone(true);
	$(".editblock#"+$id).find(".mw_save").remove();
	$test = $(".editblock#"+$id).html();
	
	$rel = $(".editblock#"+$id).attr('rel');
	
	$(".editblock#"+$id).append(clone)
	
	
	
	

	$.ajax({
		  type: 'POST',
		  url: '<? print site_url("api/content/save_block") ?>',
		  data: { id: $id, html:$test , rel:$rel ,page_id: $page_json.page.id},
          async:false,
		  success: function(data) {
			 // alert(data);
			  load_editblock($id);

		  }
		})
// init_edits();
}

function call_edit_module_iframe(url, id) {
 if(id != undefined){
	url = url + 'element_id:'+id;
	$curent_edit_element_id=id;
 }
 // alert(url);
/*
 * $("#mw_edit_module_iframe").attr('src', url);
 * $("#mw_edit_module_iframe").attr("src") =url;
 * $("#mw_edit_module_iframe").load();
 */
 
 
// $fr = window.frames['mw_edit_module_iframe'];
// $fr.clone().attr('id', 'mw_edit_module_iframe_' + id);
	
 
 
 $el = document.getElementById('mw_edit_module_iframe_'+id);
 mw_sidebar_nav('#mw_sidebar_module_edit_holder');
 $(".mw_edit_module_iframe").hide();
 if ( $el == undefined){
	 $('<iframe />', {
		    name:  'mw_edit_module_iframe_'+id,
		 // className: 'mw_edit_module_iframe',
		    id:   'mw_edit_module_iframe_'+id,
		    src:  url
		   
		}).appendTo('#mw_sidebar_module_edit_holder');
	 $('#mw_edit_module_iframe_'+id).addClass('mw_edit_module_iframe');
	} else {
		// $('#mw_edit_module_iframe_'+id).attr("src") =url;
		 $('#mw_edit_module_iframe_'+id).show();
	}

 
/*
 * var height=window.innerWidth;//Firefox if (document.body.clientHeight) {
 * height=document.body.clientHeight;//IE } //resize the iframe according to the
 * size of the //window (all these should be on the same line)
 * document.getElementById('mw_edit_module_iframe_'+id).style.height=parseInt(height-document.getElementById('admin_sidebar').offsetTop-8)+"px";
 */
 
 $('#mw_edit_module_iframe_'+id).height($("#admin_sidebar").height());
	
 $('#mw_edit_module_iframe_'+id).show();
 

 
 
 
 
 
 
 
/*
 * if(window.frames['mw_edit_module_iframe'].location != url){ if
 * (navigator.appName == 'Microsoft Internet Explorer') {
 * window.frames['mw_edit_module_iframe'].document.execCommand('Stop'); } else {
 * window.frames['mw_edit_module_iframe'].stop(); }
 * 
 * window.frames['mw_edit_module_iframe'].location = url;
 *  }
 */

 
// window.frames['mw_edit_module_iframe_' + id].location = url;
		// var call_iframe = mw.modal.iframe({src:url, width:700, overlay:true,
		// height:500, id:"module_edit_iframe"});

}


function update_module_element($new_value) {
	
	$temp = $('#'+$curent_edit_element_id).parents(".editblock") .attr("id")
	// alert($temp);
	  $('#'+$curent_edit_element_id).attr("mw_params_encoded", $new_value);
	// save_editblock($temp);

	  // mw.modal.close();
	mw.saveALL();
	  
}

mw.saveALL = function(){
	 
	
	nic_save_all(function(){
		
		$(".editblock").each(function(){
	        var id = $(this).attr("id");
	       // save_editblock(id);
	    });
		
	//	
		 
			// window.saving =false;
	 
		
		
		
		init_edits();
	
		  try {
			 // $(".module").removeAttr('contentEditable');
			 } catch (e) {
				// $(".module").attr('contentEditable', false);
			 }
		
		
	});
	

    




}



function saveSelection() {
    if (window.getSelection) {
        sel = window.getSelection();
        if (sel.getRangeAt && sel.rangeCount) {
            return sel.getRangeAt(0);
        }
    } else if (document.selection && document.selection.createRange) {
        return document.selection.createRange();
    }
    return null;
}

function restoreSelection(range) {
    if (range) {
        if (window.getSelection) {
            sel = window.getSelection();
            sel.removeAllRanges();
            sel.addRange(range);
        } else if (document.selection && range.select) {
            range.select();
        }
    }
}

function insertTextAtCursor(text) {
    var sel, range, html;
    if (window.getSelection) {
        sel = window.getSelection();
        if (sel.getRangeAt && sel.rangeCount) {
            range = sel.getRangeAt(0);
            // $t1 = ""
            range.insertNode( document.createTextNode(text) );
           // range.pasteHTML(text);
        }
    } else if (document.selection && document.selection.createRange) {
        range = document.selection.createRange();
        range.pasteHTML(text);
    }
}


function rangy_getFirstRange() {
    var sel = rangy.getSelection();
    return sel.rangeCount ? sel.getRangeAt(0) : null;
}