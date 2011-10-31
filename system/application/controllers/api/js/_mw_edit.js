/*
 * Microweber Edit - Javascript Framework
 *  
 * Copyright (c) Mass Media Group (www.ooyes.net) 
 * Licensed under the Microweber license http://microweber.com/license 
 *
 */


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


window.mw_editables_created = false;
function mw_make_editables(){
				 
				
	
 
	
				 if(window.mw_drag_started == false && window.mw_handle_hover != true ){
					
				window.mw_sortables_created = false;
				   
				   
				   $(".edit", '.mw').each(function(fld){
					   
					   
					   
				    h=    $(this).html();
				    //h=h.replace(/<strong([^>]*)>/gi,'<span style="font-weight:bold;"$1>');
				 //   h=h.replace(/<\/strong>/gi,'</span$1>');
				    
				    h = h.replace(/<(\/?)strong>|<strong( [^>]+)>/gi, '<$1span style="font-weight:bold;"$2>');
				    h = h.replace(/<(\/?)b>|<b( [^>]+)>/gi, '<$1span style="font-weight:bold;"$2>');
			        //h = h.replace(/<(\/?)em>|<em( [^>]+)>/gi, '<$1i$2>');
				    
				 //   h=h.replace(/<b([^>]*)>/gi,'<span style="font-weight:bold;" $1>');
				//    h=h.replace(/<\/b>/gi,'</span$1>');
				    
			//	    $(this).addClass('mw_layout');
				    $(this).html(h);
				    
				    
				  //  this.normalize ();
			
				    
				      });
			
				   
				   $(".unwrap", '.mw').each(function(){
					   
					   
					   $(this).replaceWith( $(this).contents() ); 
					 
				
					    
					      });
				   
				   
					 
			
				   
				   $('.Apple-style-span' ).removeClass('Apple-style-span');
				   $('.Apple-converted-space' ).removeClass('Apple-converted-space');
				
			 

	 
				 
			 if(window.mw_editables_created == false){
			  
			//	 $(".mw_layout").sortable('destroy');
				
				 $('.edit').attr('contentEditable', true);	
				 $('.col').attr('contentEditable', true);	
			  
			
		//	 document.execCommand("styleWithCSS",false,false);
			 
			  myNicEditor = window.myNicEditor;
			  $('.module', '.edit').attr('contentEditable', false);	
			  $('.module *', '.edit').attr('contentEditable', false);	
			  $('.module', '.edit').disableSelection();	
			  //$('.module *', '.edit').attr('contentEditable', false);	
			 
			  window.mw_editables_created = true
			 }

	 }
				 
				 
}
 window.mw_sortables_created = false;
 window.mw_drag_started = false;
function mw_remove_editables(){
/*
 * try { $('.edit').removeAttr('contentEditable'); } catch (e) {
 * $('.edit').attr('contentEditable', false); }
 */
	 window.mw_editing_started  = false;
	window.mw_editables_created = false;
	$('.edit').attr('contentEditable', false);
	 $('.col').attr('contentEditable', false);	
	//$('.container').attr('contentEditable', false);
//	$('.edit *').attr('contentEditable', false);
}

 
  

$('#mw_sidebar_modules_holder').die("mouseenter");
$('#mw_sidebar_modules_holder').live('mouseenter',function() {
 
 window.mw_sortables_created = false;
init_edits()
});
$('#mw_sidebar_module_edit_holder').die("mouseenter");
$('#mw_sidebar_module_edit_holder').live('mouseenter',function() {
 
	 // window.mw_sortables_created = false;
	//init_edits()
});


   



 

window.mw_making_sortables = false;

$('.edit a', '.mw').live('click',function(e) {
	 if ($('.mw_live_edit_on_off').hasClass('mw_live_edit_on_off_state_on')) {
    e.preventDefault();
	 }
    //do other stuff when a click happens
});

$('.edit .module a', '.edit').live('click',function(e) {
	 if ($('.mw_live_edit_on_off').hasClass('mw_live_edit_on_off_state_on')) {	
    e.preventDefault();
	 }
    //do other stuff when a click happens
});

 $('.module' , '.mw').live('click',function(e) {
	 if ($('.mw_live_edit_on_off').hasClass('mw_live_edit_on_off_state_on')) {
		 
		 
		 
		 
		 
		 
		 
		
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
		  load_edit_module_by_module_id($clicked_on_module) 
		  
 
		  
		  window.mw_sortables_created = false;
		  init_edits()
		 
		 
		 
		 
		 
		 
		 
		 
		 
    e.preventDefault();
			//event.preventDefault(); // this prevents the original href of the link from being opened
			e.stopPropagation(); // this prevents the click from triggering click events up the DOM from this element
			return false;
	 }
    //do other stuff when a click happens
});
 
 $( '.mw_mod_drag_handle, .container_handle,.col_handle').disableSelection();
 $(".mw_mod_drag_handle, .col_handle, .container_handle", '.edit').live("mouseenter mousedown click", function(event) {
	 window.mw_editing_started  = false;
	 window.mw_editing_started  = false;
	 window.mw_editables_created = false;
	 if ( event.type == "mouseenter" ) {
	 if( window.mw_drag_started == false){
	//	 window.mw_drag_started = true;
	  mw_remove_editables();
		
		// window.mw_sortables_created = false;
 
	 	init_edits()
	 }
	 }
	 
	 if ( event.type == "mousedown" ) {
		 
		 
		 window.mw_last_hover++;
		  $(this).parent().attr("mw_tag_edit",window.mw_last_hover );
			 
			 mw_html_tag_editor(window.mw_last_hover)
			 
			 
		// mw_remove_editables()
		 
		  window.mw_last_hover++;
	 window.mw_editing_started  = false;
	 window.mw_editables_created = false;
	 $(this).parent().attr('mw_tag_edit' ,window.mw_last_hover );
	 mw_html_tag_editor(window.mw_last_hover)
	 $('#mw_css_editor_element_id').val(window.mw_last_hover);
		 
		 event.preventDefault(); // this prevents the original href of the link from being opened
			event.stopPropagation(); // this prevents the click from triggering click events up the DOM from this element
  return false;
	 }
	 
	 if ( event.type == "click" ) {
	
	 event.preventDefault(); // this prevents the original href of the link from being opened
		event.stopPropagation(); // this prevents the click from triggering click events up the DOM from this element
return false;
//	 $(this).parent().addClass('original-placeholder' );
	 
	// $('#mw_css_editor_element_id').val($t);
	 }
	 
	 
 });
 
 
 /*
 

 $(".ui-resizable-handle", '.edit').live("mousedown mouseenter mouseup mouseleave", function(event) {
 	 
 	
 	
 	
 	
 	
 	
 	 if ($('.mw_live_edit_on_off').hasClass('mw_live_edit_on_off_state_on')) {
 	
 	if ( event.type == "mouseup" ) {
 		window.mw_drag_started = true;
 		event.preventDefault(); // this prevents the original href of the link from being opened
		event.stopPropagation(); // this prevents the click from triggering click events up the DOM from this element
	 
	return;
 		  
	 
 
 		  
 	 
 		  
 	  }  else if ( event.type == "mousedown" ) {
 	//	 $(this).attr('contentEditable', false);	
			 
 		 mw_remove_editables()
 		// init_edits()
 		// setTimeout("set_drag_started_false()",100);
		//	window.mw_sortables_created = false;

 	//	remove_sortables()
 		 
			 //	event.preventDefault(); // this prevents the original href of the link from being opened
 		 //	event.stopPropagation(); // this prevents the click from triggering click events up the DOM from this element
 				// 
 				// 
 		 
 			// return false;
 		  
 	  }  else if ( event.type == "click" ) {
 		 
 		  
 	
 		  
 		  
  
 		  
 		  
 		  
 	  }  else if ( event.type == "mouseleave" ) {
 		 
   
 		 //setTimeout("set_drag_started_false()",1500);
 		  
 	  }
 	  else if ( event.type == "mouseenter" ) {
 		  
 		  window.mw_drag_started = true;
  
 		// $(this).attr('contentEditable', false);	
 		  
 	  }
 	  
 	  
 	  else {
 	    // do something on mouseout
 		//  window.mw_making_sortables = false;
 	  }
 	
 	
 	
 	
 	
 	 }
 	
 	
 	
 	
 	
 	});
 
 
 
 */
 
 
 
 
 
 
 
 
 
 
 
 
 
 /*

 $(".mw_mod_drag_handle", '.edit').live("mousedown mouseup", function(event) {
 	 
 	
 	
 	
 	
 	
 	
 	 if ($('.mw_live_edit_on_off').hasClass('mw_live_edit_on_off_state_on')) {
 	
 	if ( event.type == "mouseup" ) {

 		//event.preventDefault(); // this prevents the original href of the link from being opened
	//	event.stopPropagation(); // this prevents the click from triggering click events up the DOM from this element
 		 window.mw_drag_started = false;
	return;
 		  
	 
 
 		  
 	 
 		  
 	  }  else if ( event.type == "mousedown" ) {
 		  
 		 window.mw_last_hover++;
		  $(this).parent().attr("mw_tag_edit",window.mw_last_hover );
			 
			 mw_html_tag_editor(window.mw_last_hover)
			 
			 
 		 mw_remove_editables()
		 
			 
			//window.mw_sortables_created = false;
		//	 init_edits();
				//event.preventDefault(); // this prevents the original href of the link from being opened
			//	event.stopPropagation(); // this prevents the click from triggering click events up the DOM from this element
			 
			//return;
 		 	//	event.preventDefault(); // this prevents the original href of the link from being opened
 		 //	event.stopPropagation(); // this prevents the click from triggering click events up the DOM from this element
 				// 
 				// 
 			// return false;
 		  
 	  }  else if ( event.type == "click" ) {
 		 
 		  
 	
 		  
 		  
 		 //event.preventDefault(); // this prevents the original href of the link from being opened
		//	event.stopPropagation(); // this prevents the click from triggering click events up the DOM from this element
		 
	//	return;
 		  
 		  
 		  
 	  }  else if ( event.type == "mouseleave" ) {
 		 
 		 // 	setTimeout("set_drag_started_false()",300);
 
 		  
 	  }
 	  else if ( event.type == "mouseenter" ) {
 		  
 		 window.mw_drag_started = true;
  
 	 
 	  }
 	  
 	  
 	  else {
 	    // do something on mouseout
 		//  window.mw_making_sortables = false;
 	  }
 	
 	
 	
 	
 	
 	 }
 	
 	
 	
 	
 	
 	});
 
 
 */
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
// 
// 
// $(".mw_layout_item", '.edit').live("mouseenter mouseleave mouseup mousedown click", function(event) {
//	
//	 if(window.mw_drag_started == false){
//	 if ( event.type == "click" ) {
//		 
//		  if( window.mw_drag_started == false){
//		   
//		   setTimeout("$( this ).children('.mw_mod_drag_handle').hide();",1000);
//		  }
//	 }
//	 
//	 if ( event.type == "mouseenter" ) {
//		  
//			if( window.mw_drag_started == false){
//				
//			if( $( this ).children('.mw_mod_drag_handle').length || $( this ).parents('.mw_mod_drag_handle').length){  
//				 
//			} else {
//				$( this ).prepend('<span class="mw_mod_drag_handle">Element</span>');
//			}
//			
//		   $( this ).children('.mw_mod_drag_handle').show();
//	 
//		   
//	 
//			}
//	 }
//	 
//	 }
//	 
//	 
//	 
// });
  
// $('.edit[contenteditable=true]')
//$('a:not("a[href^=mailto]").live('click',function(){ });
//
function set_mw_editing_started_false(){
	 window.mw_editing_started  = false;
	 
	
}
//$(".edit *:not(.mw_mod_drag_handle,.module,.resize_handle,.ui-resizable-handle)[contenteditable=false]", '.mw').live("click", function(event) {
//	if( window.mw_editing_started == false){
//	 //window.mw_editing_started  = true;
//	 setTimeout(" window.mw_editing_started  = true; window.mw_editables_created = false;  mw_make_editables()",500);	
//	  
// 	 
// 	   
//	} else {
//	//	setTimeout("set_mw_editing_started_false()",500);	
//		
//	}
//});


 window.mw_last_hover =  new Date().getTime();
window.mw_editing_started  = false;
window.mw_drag_started = false
$(".edit, .edit *:not(.mw_mod_drag_handle,img,.module,.resize_handle,.ui-resizable-handle )[contenteditable=true]", '.mw').live("click", function(event) {

	
	
	if ( event.type == "click" ) {
		if (window.console != undefined) {
			console.log('contenteditable=true  on ' + event.target.nodeName );	
		}
	  //var range = rangy.getSelection();
	 //    range= range.rangeCount ? range.getRangeAt(0) : null;
		   var range = rangy.getSelection();
			 
	//	     range= range.rangeCount;
	 
			if (window.console != undefined) {
				console.log('range is ' + range );	
			} 
	 
		 if (range == '') {
	    	 
			
	    	
	    //	 window.mw_last_hover++;
	    	 //event.target.setAttribute('mw_tag_edit', window.mw_last_hover);
	    	// setTimeout("mw_html_tag_editor(window.mw_last_hover)",300);	
	    	 
	 	 //	event.preventDefault(); // this prevents the original href of the link from being opened
			 	//event.stopPropagation(); // this prevents the click from triggering click events up the DOM from this element
 
				 // return false;
	    	 
	     } else {
	     
	     }
	}
 
	
});

function mw_outline_by_edit_tag($tag_id){
	mw.outline.removeAll();
	mw.outline.init("*[mw_tag_edit='"+$tag_id+"']", '#DCCC01');

}

function mw_apply_code_from_editor(){
	$htm =  $('#mw_edit_code_holder').val();
	$v  =  $('#mw_css_editor_element_id').val();
	
	$("*[mw_tag_edit='"+$v+"']",'.edit').html($htm);
	
	
}




window.html_editor_code_posted = false
window.html_editor_code_updated = false
$(".edit div:not(.mw_mod_drag_handle,.col_handle, .container_handle ,img,.module,.module *,.resize_handle,.ui-resizable-handle),.edit p,.edit table", '.mw').live("mouseup", function(event) {
	//mw.modal.close();
	if ($('.mw_live_edit_on_off').hasClass('mw_live_edit_on_off_state_on')) {
					$html = $(this).html();
					window.mw_last_hover++;
					
				 
			
					if(window.html_editor_code_posted == false){
					$all_attr = {};
					$all_attr.file=window.mw_last_hover+'.php';
					$all_attr.content=$html ;
					window.html_editor_code_posted = true;
					 url1= '{SITE_URL}api/content/html_editor_write_cache_file';
					 $.post(url1,$all_attr,function() {
					//	 alert($all_attr);
						 window.html_editor_code_updated = true;
						 window.html_editor_code_posted = false;
					 }); 
					}
					 
					 
					 
				
			 
					
	//	$html =  event.target.innerHTML;
					
				
					 $('#mw_edit_code_holder').val($html);
					 $('#mw_css_editor_element_id').val(window.mw_last_hover);
					
					//alert($html );
					 var rightArrowParents = [],
				     elm,
				     entry;
				$nom = 0;
				 for (elm = event.target; elm; elm = elm.parentNode) {
				    if($nom < 15){
				    	
				    	
					    	entry = elm.tagName.toLowerCase();
					        cl = elm.className.toLowerCase();
					        cl = cl.replace("ui-sortable", '');
					        cl = cl.replace(" ui-sortable", '');
					        cl = cl.replace("ui-sortable ", '');
					        
					        if (entry === "html") {
					            break;
					        }
					        if (entry === "body") {
					            break;
					        }
					        
					        if (cl == "edit") {
					            break;
					        }
					        
					        if (cl == "mw") {
					            break;
					        }
					        
					        elm.setAttribute('mw_tag_edit', window.mw_last_hover);
					        
					        
					        if (elm.className) {
					         //  entry += "." + elm.className.replace(/ /g, '.');
					        	//  entry +=  cl;
					        }
					        
					        
					     
					        entry = "<a onmouseout='mw.outline.removeAll();'   onmouseover='mw_outline_by_edit_tag("+window.mw_last_hover+")'  href='javascript:mw_html_tag_editor("+window.mw_last_hover+")'>" + entry + "</a>"
					        
					        rightArrowParents.push(entry);
					        window.mw_last_hover++;
				     $nom ++;
				    }
				 }
				 rightArrowParents.reverse();
				 
				 
				 
				 $( '#mw_dom_element_path' ).html(rightArrowParents.join(">"));
				 
				 
	 }
	
	
	
	
	
	event.preventDefault(); // this prevents the original href of the link from being opened
 	event.stopPropagation(); // this prevents the click from triggering click events up the DOM from this element
		// 
		// 
	 return false;
})

$(".edit,.edit *:not(.mw_mod_drag_handle,.col_handle, .container_handle ,img,.module,.module *,.resize_handle,.ui-resizable-handle )[contenteditable=false]", '.mw').live("mousedown", function(event) {
	

	
	


	
		
		
		
		
	
	if(   window.mw_drag_started == false){
		var randomCssClass = "rangyTemp_" + (+new Date());
		if(event.target.nodeName != undefined){
			if (window.console != undefined) {
 			console.log('mousedown on ' + event.target.nodeName );	
 		}
		
			
			 $is_ce_edit=$('.edit').attr('contentEditable');
			
			 if( $is_ce_edit == undefined || ( $is_ce_edit != 'true' || $is_ce_edit == false) ){
				 window.mw_drag_started = false 
window.mw_handle_hover = false
if (window.console != undefined) {
	console.log('making the ditable on mousedown' );	
	
}
				 setTimeout("set_drag_started_false()",100);	
				 window.mw_editables_created = false; 
				 remove_sortables();
				 mw_make_editables()
			 } else {
				 
				 if (window.console != undefined) {
						console.log('$is_ce_edit is ' + $is_ce_edit  );	
						
					}
				 
			 }
		}
	} else {
	
		setTimeout("set_drag_started_false()",500);	
	}
});




$(".module", '.edit').live("mouseenter mouseleave mouseup mousedown click", function(event) {
	 
	
	
	
	
	
	
	 if ($('.mw_live_edit_on_off').hasClass('mw_live_edit_on_off_state_on')) {
	
	if ( event.type == "mousasdasdaeup" ) {


		  if (window.console != undefined) {
				console.log('mouseup on module '  );	
				
			}
		  
		  
		
			
		  
		  
		  window.mw_making_sortables = false;
		  
	  }  else if ( event.type == "mousedown" ) {

		  if (window.console != undefined) {
				console.log('mousedown on module ' );	
			}
		  $( this ).children('.mw_mod_drag_handle').show();
		  $('.edit .mw_non_sortable').removeClass('mw_non_sortable');
		 // window.mw_making_sortables = true;
 		//  mw_remove_editables();
			
		 //	init_edits()
		 	
		 		event.preventDefault(); // this prevents the original href of the link from being opened
		 	event.stopPropagation(); // this prevents the click from triggering click events up the DOM from this element
				// 
				// 
			 return false;
		  
	  }  else if ( event.type == "mouseup" || event.type == "click" ) {
		  if (window.console != undefined) {
				console.log('click on module ' );	
			}
		  window.mw_making_sortables = false;
		  
		$clicked_on_module = 	$(this).attr('module_id');
		  
		  
		  
		  load_edit_module_by_module_id($clicked_on_module) 
		  
			//event.preventDefault(); // this prevents the original href of the link from being opened
		 	//event.stopPropagation(); // this prevents the click from triggering click events up the DOM from this element
				// 
				// 
			// return false;
		  
		  window.mw_sortables_created = false;
		  init_edits()
		  
			event.preventDefault(); // this prevents the original href of the link from being opened
		 	event.stopPropagation(); // this prevents the click from triggering click events up the DOM from this element
				// 
				// 
			 return false;
		  
	  }  else if ( event.type == "mouseleave" ) {
		  if (window.console != undefined) {
				console.log('mouseleave on module ' );	
			}
		  if( window.mw_drag_started == false){
			  
				$(this ).resizable('destroy');
		   
		   
		  
		  }
		  
	//	  $( this ).disableSelection();
		//	$( this ).children().disableSelection();
		//	$(this).attr('contentEditable', false);
		//	$( this ).children().attr('contentEditable', false);
		  
		//	$( '.mw_non_sortable' ).removeClass('mw_non_sortable');
			//$( this ).children().removeClass('mw_non_sortable');

		//  init_edits()
		  
	  }
	  else if ( event.type == "mouseenter" ) {
		  
			if( window.mw_drag_started == false){
				
				
				
				
				
				
			
			 	 $(this).resizable({
							 handles: 'e',
						//	 handles: {'w': '.resize_handle'},
						//	 ne:'span.resize_handle', se:'div.wnd_bottom_right', sw:'div.wnd_bottom_left', nw:'div.wnd_top_left' ,
						//	 cancel: '*:not(.resize_handle)',
					//		 autoHide: true ,
							  resize: function(event, ui) {
			 		window.mw_drag_started= true;
								 
							 },
							 
							 start: function(event, ui) {
									window.mw_drag_started= true;
						//		 window.mw_drag_started = true;
							// window.mw_handle_hover = true;

							// $(this).next(".col").css('background-color', 'red');
							 //$(this).next(".col").attr('also_resize', window.mw_last_hover);
							 
					//		 $(this).not('img').css("height", 'auto');
							 
							 
						},
						
						
						//alsoResizeReverse: "[also_resize='"+window.mw_last_hover+"']",
						 stop: function(event, ui) {
			 
					        $(this).not('img').css("height", 'auto');
					        $(this).css("position", '');
					        $(this).css("top", '');
					        $(this).css("left", '');
					        window.mw_drag_started = false;
					        window.mw_drag_started = false;
						//	setTimeout("   window.mw_drag_started = false;",500);
						} 
							 
						 
						});
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				 // window.mw_sortables_created = false;
				//  init_edits()
				
		  if (window.console != undefined) {
				console.log('mouseenter on module ' );	
			}
		  
		  
			$module_title = 	$(this).attr('module_title');
		//  $( this ).children('.mw_mod_drag_handle').show();
	  	// 
			if( $( this ).children('.mw_mod_drag_handle').length || $( this ).parents('.mw_mod_drag_handle').length){  
				 
			} else {
				$( this ).prepend('<span class="mw_mod_drag_handle">'+$module_title+'</span>');
			}
			
		   $( this ).children('.mw_mod_drag_handle').show();
			
		   
		   if( window.mw_editing_started == false){
		 //  position1223 = $(this).position();
			  
		//	  $( this ).children('.mw_mod_drag_handle:first').css({top:position1223.top,left:position1223.left+2});
		   }
		   //init_edits()
			}
			
			
			
			
			

//			$(this).resizable( "destroy" )
//			
//				$(this).resizable({
//				handles: 'n, s, e, w',
//				cancel: ':input,option',
//				start: function(event, ui) {
//				
//				window.mw_drag_started = true;
//			 
//				 
//				
//			},resize: function(event, ui) {
//				window.mw_drag_started = true;
//			
//			},
//			stop: function() {
//				//	window.mw_drag_started = false;
//				  	setTimeout("set_drag_started_false()",1000);
//				} ,
//				  
//				helper: "ui-resizable-helper"
//			});
//			
			
 
  
		  
	  }
	  
	  
	  else {
	    // do something on mouseout
		  window.mw_making_sortables = false;
	  }
	
	
	
	
	
	 }
	
	
	
	
	
	});
 
 



function mw_resize_admin_sidebar(){
	 var h1 = $(window).height();
	 var h2 = $('#mw_toolbar').height();
	 $('#admin_sidebar').height(h1-45);	
	 
	 
	 var h3 = $('#admin_sidebar').height();
	// var h4 = $('#admin_sidebar').height(); 
	 $('.mw_edit_module_settings_iframe').height(h1-(145));	
	 $('#module_bar_resp').height(h1-(145));	
	 $('#mw_sidebar_css_editor_holder').height(h1-(145));	
	 
	 
	 var viewportwidth;
	 var viewportheight;
	 
	 // the more standards compliant browsers (mozilla/netscape/opera/IE7) use window.innerWidth and window.innerHeight
	 
	 if (typeof window.innerWidth != 'undefined')
	 {
	      viewportwidth = window.innerWidth,
	      viewportheight = window.innerHeight
	 }
	 
	// IE6 in standards compliant mode (i.e. with a valid doctype as the first line in the document)

	 else if (typeof document.documentElement != 'undefined'
	     && typeof document.documentElement.clientWidth !=
	     'undefined' && document.documentElement.clientWidth != 0)
	 {
	       viewportwidth = document.documentElement.clientWidth,
	       viewportheight = document.documentElement.clientHeight
	 }
	 
	 // older versions of IE
	 
	 else
	 {
	       viewportwidth = document.getElementsByTagName('body')[0].clientWidth,
	       viewportheight = document.getElementsByTagName('body')[0].clientHeight
	 }
	
	 
	 
	 
	  $w1 =  viewportwidth;
	  $w2 = $('#wrapper').width();
	//  $w2 =  document.body.clientWidth;
	  if(($('#wrapper').size()) > 0){
	  $w2 = $('#wrapper').width();
	  }
	  
	  if(($('.wrapper').size()) > 0){
		//  $w2 = $('.wrapper').width();
		  }

	  
	  if($w1 <  $w2+200){
		  if (window.console != undefined) {
	 			console.log('Body size '+$w2 );	
	 			console.log('Window size '+ $w1);	
	 		}
		  
		//  $('body').width($w2+650+'px');
	  }
	  
	  
	
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
			
			
	 
	 
	//
}

$(function(){
    $(window).resize(function(){
    	mw_resize_admin_sidebar()
    });
});
function set_drag_started_false(){
	
	
	if (window.console != undefined) {
			console.log('mw_drag_started set to false   ');	
		}
	window.mw_drag_started = false;

}

function set_handle_hover_false(){
	window.mw_handle_hover = false;

}
function mw_make_draggables(){
	
 
	
	 }
function remove_sortables(){
	if( window.mw_editables_on_page == undefined){
		window.mw_editables_on_page  = $( ".edit" );
	}
	
	if (window.console != undefined) {
 			console.log('remove_sortables   ');	
 		}
	
	
	
//	if(window.mw_sortables_created == true){
	
	try
	  {
		   $( '.col' ).resizable('destroy');
		$('.edit').sortable('destroy');
		$('.mw_layout').sortable('destroy');
		
	  }
	catch(err)
	  {
	  //Handle errors here
	  }
	
	
	
	
	
	//$('.edit *').sortable('destroy');
	 window.mw_sortables_created = false;
	//}
}
var wscrolltop = 0;

/*$('.edit').keyup(function (e) {
	 if(	window.mw_editables_created == undefined || window.mw_editables_created == false  ){
	    if (e.keyCode == 46) {
		    	if (window.console != undefined) {
		 			console.log('delete key was pressed   ' + window.mw_editables_created);	
		 			 mw_html_tag_delete();
		 		}
	    }
	 }
});*/

 

function init_edits(){
	//mw_remove_editables() 
	//mw_make_editables()
//	mw_make_draggables();
	
	
	
//	window.mw_sortables_created = false;
	if( window.mw_sortables_created == false && window.mw_drag_started == false){
 
	
		
		mw_set_handles()
		
		
	//	$('.mw_mod_drag_handle' ).remove();
	
		
		if( window.mw_editables_on_page == undefined){
			window.mw_editables_on_page  = $( ".edit" );
		}
		
		if (window.console != undefined) {
 			console.log('init_edits   ');	
 		}
	
		
		mw_sidebar_make_sortables();
		
		
		
		//$('.edit .module' ).attr('contentEditable', false);
		//$('".edit .module *' ).attr('contentEditable', false);

 
		bind_module_edit_iframe_click()
		
		
		
		
		
		
		
		
		
		
		
	 window.mw_sortables_created = true;
	
	 start =  window.mw_last_hover;
	  if(start == undefined){
		  var start = new Date().getTime();
		  window.mw_last_hover = start;
	  }
	  window.mw_last_hover++;
	
/*	  $(".mw_put_module_ids").each(function (i) {
			$(this).attr('module_id', 'module_'+window.mw_last_hover);
	      });*/
	  
		put_new_ids = $(".mw_put_module_ids");
		put_new_ids_length123 = put_new_ids.length;
		//length = $edit_fields.length();
	 
	tempArray=new Array(); 
		for (var ed=0;ed<put_new_ids_length123; ed++) {  
			put_new_id = put_new_ids[ed];
			put_new_id.setAttribute('module_id','module_'+window.mw_last_hover++);
		} 
		
		
		
	 
 
	 
	 
	 var sort_opts = {
			//	forcePlaceholderSize: true,
			 forcePlaceholderSize: true,
				forceHelperSize : true ,
				tolerance: 'pointer',
				//tolerance: 'intersect',
			//	iframeFix: true,
			   //cancel: '.module > * :not(:has(.module)) ',
			    cancel: '.mw_non_sortable',
			 	placeholder: "to_here_drop",
			 	placeholderElement: 'div',
			//	placeholderElement: '.edit *',
				create:function(event, ui){
							      
//		 if( $(ui.item).has('.close').length == 0 ) {
//		..		$(ui.item).prepend('<a href="#" class="close" onclick="$(this).parent().remove();">x</a>');
//			}   
							         
	 		},
	 		over: function(e, ui) {
	 			
	 			
	 			
				
	 			
	 			
	 			
	 			
	 			
	 			
	 			
	 			//  window.mw_drag_started = true;
           //     $(this).addClass('to_here_drop'); //Adding a class that will add a border
           //     ui.placeholder.css($.data(ui.sender[0], 'ui-sortable').placeholderElement.offset()); //So I have to relocate the placeholder by two pixels
        },
		     beforeStop: function (event, ui) {
//						    	 if( ui.offset !== undefined )
//						    	  ui.helper.css('margin-top', 0);
//						    	 
        	  window.mw_drag_started = true;
						    	 
						   // 	 $( ".edit .mw_non_sortable" ).removeClass('mw_non_sortable');
						    	 
						    	 
						    	 
						    	 
		    	},


			//	items: '.module',
				//items: '*',
				items: '*:not(.mw_mod_wrap)',
				//items: '*:not(:has(.module))',
				//revert: true,
				handle : '.mw_mod_drag_handle',
				
				//zIndex: 5000,
			//	 grid: [5, 5],
		 	//opacity: 0.6,
		   	distance: 1,
				
							update: function(event, ui) {
		    	//	$( ".edit .mw_non_sortable" ).removeClass('mw_non_sortable');
		    		
		    		
		    		
		    		
							 		//window.mw_drag_started = false;
		    //		setTimeout("set_drag_started_false()",1500);
							 		
							            if(this.id == 'sortable-delete') {
							                // Remove the ele ment dropped on #sortable-delete
							                $('#'+ui.item.attr('id')).remove();
							            } else {
							                // Update code for the actual sortable lists
							            	// window.mw_sortables_created = false;
							            //	 init_edits()
							            }
							          //  $( ".edit .module > *" ).disableSelection()
							     //  	 window.mw_sortables_created = false;
										
							     	// init_edits()
										
							            //init_edits();
						//	       window.mw_drag_started = false;
							   
							     	//	 window.mw_sortables_created = false;
										// init_edits();
							        	//	setTimeout("set_drag_started_false()",1500);
							     //   nic_save_all(mw_load_history_module, true);
							    //	bind_module_edit_iframe_click()
							        } ,
							       
							        
							        
							        
							        start : function(event, ui) { 
							      // 	mw_remove_editables();
							        window.mw_drag_started = true;
							        
							       // alert(1);
							   //	$( '.edit').append('<div class="mw_dropable_generated"></div>');
							//   	$( '.edit').prepend('<div class="mw_dropable_generated"></div>');
							        
						//	    	$('.container *', '.edit' ).not('.module').removeClass('mw_mod_wrap'); 
							  //  	$('.container', '.edit' ).not('.module').removeClass('mw_mod_wrap'); 
							    //	$('.module', '.edit' ).removeClass('mw_mod_wrap'); 
							    	 
							  	  if (window.console != undefined) {
										console.log('mw_drag_started ' );	
									}
							     
							        },
							        
							        
							        
							        
							        
							        
							        
							        
							        
							        stop: function(event, ui) { 
							        	if (ui.item.hasClass("mw_mod_wrap")) {
							                // This is a new item
							               // ui.item.remove();;
							            ui.item.html("<b>HI mw_mod_wrap bug in mw_edit.js see there.... ??? maybe threre is setting of - 	items: '*:not(.mw_mod_wrap)' -, in the init_edits</b>");
							            }
					
							     //   	$(".edit").enableSelection()
							      //  	$('.container', '.edit' ).children().removeClass('mw_mod_wrap');
							        	if (ui.item.hasClass("mw_mod_wrap_main")) {
							        	ui.item.css('zoom', '');
							        	ui.item.css('filter', '');
							        	ui.item.css('margin-top', '');
							        	ui.item.css('opacity', ''); 
							        	}
							    	//	setTimeout("set_drag_started_false()",1500);
							        //	mw_make_editables();
							        	   window.mw_drag_started = false;
							    		 mw_set_handles()
							    		 
							    		 
							    		 window.mw_sortables_created = false;
		 init_edits();
							    		 
							        	},
		        
			 
			

			
				receive: function(event, ui) {
				//copyHelper= null;
				
						//	window.mw_drag_started = false;
							        	  	setTimeout("set_drag_started_false()",500);
//							$( ".edit .module_draggable" ).css("display","");
//			
//							$( ".edit .module_draggable" ).removeClass('module_draggable');
//							$( ".edit .mw_put_module_ids" ).removeClass('mw_put_module_ids');
//							$( ".edit .mw_no_module_mask" ).removeClass('mw_no_module_mask');
					//		$( ".edit .mw_mod_wrap" ).removeClass('mw_mod_wrap');
							//$( ".edit .mw_mod_wrap" ).remove();
							
							 
							$( ".edit .mw_show" ).show();
							$( ".edit .mw_show" ).removeClass('mw_show');
						//	$( ".edit .module" ).empty();
							
							$( ".edit *" ).attr('sizcache', '');
							$( ".edit *" ).attr('sizset', ''); 
							
							
							$( ".edit .js_mod_remove" ).remove();
							// window.mw_sortables_created = false;
						//	 init_edits();
							//mw.saveALL();
							// window.mw_sortables_created = false;
							// mw_make_editables()
						//	init_edits()
							bind_module_edit_iframe_click()
							
			}
			
			
		//	,connectWith: ".edit:not(.mw_mod_wrap)"
			};
	
	 window.mw_editables_on_page.sortable('destroy');
	
 
	
//	$('.module *:not:(.mw_mod_drag_handle)', '.edit' ).addClass('mw_mod_wrap');
 //	$('.module', '.edit' ).addClass('mw_mod_wrap_main');
 //	$('.mw_mod_drag_handle', '.module' ).removeClass('mw_mod_wrap_main');
 
 	

	
	
	
//	$( ".edit" ).selectable();
	$edit_fields = window.mw_editables_on_page;
	length123 = $edit_fields.length;
	//length = $edit_fields.length();
	 if ( $.browser.msie ) {
		 
	 } else {
		// sort_opts.revert = 300;
    	 
    	 
	 }
tempArray=new Array(); 
	for (var ed=0;ed<length123; ed++) {  
		$edit_field_id = $edit_fields[ed].id;
		if (window.console != undefined) {
		console.log($edit_field_id);
		}
	//	$selects1 = $selects1 +  ', #'+$edit_field_id 
		//tempArray[ed]='#'+$edit_field_id;
	//	$( '#'+$edit_field_id+'').sortable(sort_opts).sortable( "option", "connectWith", ".col" );
		//$( '#'+$edit_field_id+'').prepend('<div class="mw_dropable_generated"></div>');
	////	$( '#'+$edit_field_id+'').append('<div class="mw_dropable_generated"></div>');
 
	} 
	
	
	$(".edit").sortable('destroy');
	$(".container").sortable('destroy');
	$(".col").sortable('destroy');
	$(".row").sortable('destroy');
	
	$(".container_holder").sortable('destroy');
	
	
	$(".edit").sortable({
		  forcePlaceholderSize: true,
		 	forceHelperSize : true ,
 	tolerance: 'pointer',
			revert:true,
		 //	   helper:'clone',
		//	containment: '.container:not(.row)',  
		// 	  containment: '.container_holder,.container:not(.row)',  
			//tolerance: 'intersect',
		// 	iframeFix: true,
		   //cancel: '.module > * :not(:has(.module)) ',
	    //cancel: '.mw_mod_wrap',
		//	placeholder: "to_here_drop",
		//	placeholderElement: 'div',
	 //containment: '.edit',
	  //  items : 'div',
	 	items: '.edit *:not(.mw_mod_wrap, .row), .container:not(.mw_mod_wrap),.module:not(.mw_mod_wrap), p:not(.mw_mod_wrap), br:not(.mw_mod_wrap), .col:not(.mw_mod_wrap) > *, .container_holder:not(.mw_mod_wrap) > *',
	 	//items: '.container,.module,.col *.module, p, br',
	//	items: '*:not(.mw_mod_wrap)', 
			//revert: true,
			handle : '.mw_mod_drag_handle:not(.col_handle),.container_handle:not(.col_handle)',
			//cancel: '.row',
			  cancel: '.mw_mod_wrap',
		 placeholder: "to_here_drop",
		 placeholderElement: 'div',
			
			zIndex: 6000,
		//	 grid: [5, 5],
	 	 opacity: 0.6,
	   	distance: 5 ,
	   	
	//placeholder: "ui-state-highlight",
	stop: function() {
		mw_set_grid()
		
			$( ".edit .js_mod_remove" ).remove();
		$( ".js_remove" ).remove();
		
		$('.mw_dropable_generated').filter(function (index) { 
			    return $(this).children().length < 1; 
			}).remove();
		
	  	setTimeout("set_drag_started_false()",500);
	 // 	$( '.container').removeClass('to_here_drop2');
	  	mw_set_handles();
		
	},	start: function() {
		
		
	//	$( '.container').addClass('to_here_drop2');
		
	 
		
		
	   	
  
	   	
		window.mw_drag_started = true;
	},receive: function(event, ui) {
		//copyHelper= null;
		
		//	window.mw_drag_started = false;
			        	  	setTimeout("set_drag_started_false()",500);
//			$( ".edit .module_draggable" ).css("display","");
//
//			$( ".edit .module_draggable" ).removeClass('module_draggable');
//			$( ".edit .mw_put_module_ids" ).removeClass('mw_put_module_ids');
//			$( ".edit .mw_no_module_mask" ).removeClass('mw_no_module_mask');
	//		$( ".edit .mw_mod_wrap" ).removeClass('mw_mod_wrap');
			//$( ".edit .mw_mod_wrap" ).remove();
			
			 
			$( ".edit .mw_show" ).show();
			$( ".edit .mw_show" ).removeClass('mw_show');
		//	$( ".edit .module" ).empty();
			
			$( ".edit *" ).attr('sizcache', '');
			$( ".edit *" ).attr('sizset', ''); 
			
		
			$( ".edit .js_mod_remove" ).remove();
			// window.mw_sortables_created = false;
		//	 init_edits();
			mw.saveALL();
			// window.mw_sortables_created = false;
			// mw_make_editables()
		//	init_edits()
			bind_module_edit_iframe_click()
			
}
	}).sortable("option", "connectWith", ".edit, .col, .container, .container_holder > *,.col > *" )
	 //"option", "connectWith", ".row" //"option", "connectWith", ".edit *" 
	
//	

	
	
	$(".row", '.edit').sortable({
		  forcePlaceholderSize: true,
 	forceHelperSize : true ,
	//revert:true,
 tolerance: 'pointer',
   //helper:'clone',
	//tolerance: 'intersect',
//		//	iframeFix: true,
//		   //cancel: '.module > * :not(:has(.module)) ',
	    cancel: '.module',
//	//	placeholder: "to_here_drop",
//		//	placeholderElement: 'div',
//	//	containment: '.edit *',
//containment: '.row:not(.col)',  
	   //containment: '.row',
//		//  axis: 'x', 
//
//	 
			items: '.col',
//		//items: '*:not(:has(.module))',
//		//revert: true,
 	handle : '.col_handle' ,
		  cancel: '.module',
	 placeholder: "to_here_drop",
	// placeholderElement: 'div',
//			 placeholderElement: 'div',
////			
//	zIndex: 6000,
////		//	 grid: [5, 5],
// 	 opacity: 0.6,
//   	distance: 1 ,
////	   	
////	//placeholder: "ui-state-highlight",
stop: function() {
		mw_set_grid()
	//	window.mw_drag_started = false;
		//  $(".row").sortable('refresh');

		 
		
	  	setTimeout("set_drag_started_false()",500);
	},	start: function() {
		window.mw_drag_started = true; 
	}
	}).sortable("option", "connectWith", ".row");
	

 	$("div.module", '.edit').find('.module').addClass('mw_mod_wrap');
	$("div.module", '.edit').find('.module').find('.mw_mod_drag_handle').addClass('mw_mod_drag_handle_inner');
 
		$('.edit .module').attr('contentEditable', false);
		$('.edit .module *').attr('contentEditable', false);
		
 	 
	
	}
	
	
	
	
	/*$(".container_holder").sortable({
		forcePlaceholderSize: true,
	 	forceHelperSize : true ,
	 	 placeholder: "to_here_drop",
		 placeholderElement: 'div',
	 	items: '.container'
	}).sortable("option", "connectWith", ".container_holder");
	
	*/
	
	
	
	
	
	
	
	
	
	
	
	
	bind_module_edit_iframe_click()
	
	
	
	
	
 }
function returnHex(num) {
    // Hex can store 16 different values in 1 character
    if (num == null) return "00";
    num = num.length < 2 ? "0" + num : num
    return num.toString(16);
}
function rgbConvert(str) {
	
	$see  = str.indexOf("rgb") ;
	if($see != -1){
		str1 = str
	   str = str.replace('rgb', '')
	    str = str.replace('(', '')
	      str = str.replace(')', '')
	       str = str.split(",");
	   
		 var red = returnHex(str[0]);
	     var green = returnHex(str[1]);
	     var blue = returnHex(str[2]);
	     var hex = "#" + red + green + blue;
//	   str[0] = parseInt(str[0], 10).toString(16).toLowerCase();
//	   str[1] = parseInt(str[1], 10).toString(16).toLowerCase();
//	   str[2] = parseInt(str[2], 10).toString(16).toLowerCase();
//	   str[0] = (str[0].length == 1) ? '0' + str[0] : str[0];
//	   str[1] = (str[1].length == 1) ? '0' + str[1] : str[1];
//	   str[2] = (str[2].length == 1) ? '0' + str[2] : str[2];
	   
	   
	 
	   
	   
	   
	   
	   
	   str2 =  '#' + str.join("");
	   if (window.console != undefined) {
			 
			  console.log('rgbConvert '+str1 +' to '+str2);
			 }
			 
	   
	   return (str2);
	} else {
		  return str
	}
	   
	   
	}

function mw_set_handles(){
 if( 1 == 1){
 	 
	 
	 $new_holders = 0;
	 $(".container_holder").each(function() { 
		
		    if($(this).children().length < 1) {
		    	$( this ).html('<div class="js_remove_empty container_holder_placeholder"><span class="js_remove mw_mod_wrap "><br class="js_remove mw_mod_wrap container_holder_placeholder">Drop box here<br class="js_remove mw_mod_wrap"></span></div>');
		    	$new_holders ++;
		 
		    }
		       // $(this).remove() 
		 
		});
		
	 if( $new_holders > 0){
		 window.mw_sortables_created = false;
		 init_edits();
	 }
	 
	 
	 $(".js_remove_empty").each(function() { 
			
		    if($(this).children().length < 1) {
		    	$(this).remove() 
		 
		    }
 
		});
	 
 	
 	$(".mw_mod_drag_handle").each(function() {
 	 position123 = $(this).parent().position();;
 	 if(position123 != undefined){
 			 if(position123[0] != undefined){
 				  if (window.console != undefined) {
 			 				}
 				  $(this).css({position: 'absolute', top:position123[0],left:position123[1]+20});
 		 }
 	 }
 	});
	
 
 		
 	$(".container").each(function() {
	 	 position123 = $(this).position();;
	 	 if(position123.top != undefined){
	 			 if(position123.left != undefined){
	 				  				position123.top = Math.round(position123.top);
	 				position123.left = Math.round(position123.left);
	 			 
	   $(this).children('.container_handle:first').css({position: 'absolute', top:position123.top+'px',left:position123.left-20+'px', zIndex:1500});
	 		 }
	 	 }
	 	});
 	
 	
 	
	$(".col").each(function() {
	 	 position123 = $(this).offsetParent().position();

	 	 if(position123.top != undefined){
	 			 if(position123.left != undefined){
	 				  				position123.top = Math.round(position123.top);
	 				position123.left = Math.round(position123.left);
	 				 
	   $(this).find('.col_handle:first').css({position: 'absolute', top:position123.top+'px',left:position123.left+20+'px', zIndex:1600});
	 		 }
	 	 }
	 	});
 
 	
 	
 	
 	
 
	
 	}
	
	
	
	 
	
}

function bind_module_edit_iframe_click(){
	 
	
	
}
 $(".container_handle", '.edit').live("mouseenter mouseleave click mousedown", function(event) {
	 //$( '.container' ).removeClass('mw_mod_wrap');
	
	//  $(".container_handle").removeClass('mw_mod_wrap');
	 if ( event.type == "mouseenter" ) {
	// window.mw_drag_started = true;
		// $( '.mw_mod_wrap' ).removeClass('mw_mod_wrap');
		 window.mw_handle_hover = true;
		 window.mw_drag_started = true;
		 window.mw_handle_hover = true;
	 }

	 if ( event.type == "mouseleave" ) {
		 set_handle_hover_false()
			//setTimeout("set_handle_hover_false()",500);
		 }
	 
	 if ( event.type == "mousedown" ) {
		 window.mw_handle_hover = true;
		 window.mw_drag_started = true;
		 window.mw_handle_hover = true;
		 //mw_remove_editables();
	//	 mw_set_handles()
		 }
	 
	 
	
	 
		
 });
 
 
 /*
 $(".ui-resizable-handle", '.container').live("mouseenter mouseleave click", function(event) {
  
		 if ( event.type == "mouseenter" ) {
		// window.mw_drag_started = true;
			 window.mw_drag_started = true;
			 window.mw_handle_hover = true;
		  
		 }
		 
		//	position123 = $(this).parent().position();
			
			 
 
			//  $( this ).children('.mw_mod_drag_handle').css(position123);
		 if ( event.type == "mouseleave" ) {
		//	 window.mw_drag_started = false;
			//	setTimeout("set_handle_hover_false()",500);
			 }
		 
		 if ( event.type == "click" ) {
			 window.mw_drag_started = false;
			 mw_remove_editables();
			 }
			
	 });*/
 
//
// $(".ui-resizable-helper", '.edit').live("mouseenter mouseleave", function(event) {
//	// $( '.container' ).removeClass('mw_mod_wrap');
//	// $( '.container' ).children().removeClass('mw_mod_wrap');
//	//  $(".container_handle").removeClass('mw_mod_wrap');
//	 if ( event.type == "mouseenter" ) {
//	// window.mw_drag_started = true;
//		 window.mw_handle_hover = true;
//	 
//	 }
//	 
//	 if ( event.type == "mouseleave" ) {
//			setTimeout("set_handle_hover_false()",500);
//		 }
//		
 // });
 
 $("a", '.edit').live("mousedown click", function(event) {
	 if ( event.type == "mouseenter" ) {
		
		 
		 
	 }
	 if ( event.type == "click" ) {
		 window.mw_last_hover++;
		 window.mw_last_hover++;
		 
		  if (window.console != undefined) {
				console.log('click on a ' );	
			} 
		 $( this ).attr('mw_tag_edit', window.mw_last_hover);	
		 //mw_html_tag_editor(window.mw_last_hover)
		 mw_html_tag_editor(window.mw_last_hover)
		 
		 event.preventDefault(); // this prevents the original href of the link from being opened
		 event.stopPropagation(); // this prevents the click from triggering click events up the DOM from this element
			return false;
		 
	 }
	 
	 
	
	 
 }) 
 
 $("img", '.edit').live(" mouseenter mousedown click", function(event) {
	 if ( event.type == "mouseenter" ) {
		
		//$(this).resizable('destroy');
//			 $(this).resizable({
//				 handles: 's, e',
//				 autoHide: false ,
//				 start: function(event, ui) {
//				  window.mw_handle_hover = true;
// 
//				 
//			},
//			//alsoResizeReverse: "[also_resize='"+window.mw_last_hover+"']",
//			 stop: function(event, ui) { 
//				 
//		     
//
//			 setTimeout("set_handle_hover_false()",1500);
//			} 
//				 
//			 
//			});
		 
	 }
	 if ( event.type == "click" ) {
		 window.mw_last_hover++;
		 window.mw_last_hover++;
		 
		  if (window.console != undefined) {
				console.log('click on img ' );	
			} 
		 $( this ).attr('mw_tag_edit', window.mw_last_hover);	
		 //mw_html_tag_editor(window.mw_last_hover)
		 mw_html_tag_editor(window.mw_last_hover)
		 
		 event.preventDefault(); // this prevents the original href of the link from being opened
		 event.stopPropagation(); // this prevents the click from triggering click events up the DOM from this element
			//return false;
		 
	 }
	 
	 
	
	 
 }) 
 
 
$(".container", '.edit').live("mouseenter mouseleave mouseup mousedown click", function(event) {
	
	
	 
	//if( window.mw_editing_started == false){
	
	 
	 if ( event.type == "mouseenter" ) {
 

			if( 1 == 1){
				
 
				 
		  if (window.console != undefined) {
				console.log('mouseenter on container ' );	
			} 
		  
		 
		 
 
			if( $( this ).children('.container_handle').length || $( this ).parents('.container_handle').length){  
				 
			} else {
				$( this ).prepend('<div class="container_handle">box</div>');

			}
			
 	
		
			
			mw_set_handles()
 
			 
			 //$( this ).children('.mw_mod_drag_handle').not('.col_handle').css(position123);
			  
			$('.container_handle').html('box');
			  
			  
		//	  $( this ).find('.col').attr('also_resize', window.mw_last_hover);
			  
			  
			
		   $('.container_handle').show();
		   
		   

		 //  $( '.col' , $(this)  ).css("text-decoration", "underline")

		
	
			
				
		    
		   
		   //init_edits()
			} 

		  
	  }
	 if ( event.type == "mouseleave" ) {
		 if( window.mw_drag_started == false){
 
	
		 
			 
		 }
	 
	 }
	 
	 if ( event.type == "click" ) {
	
		   
		// setTimeout("set_drag_started_false()",500);
	 }
	 
	
	//}
	 
});

 $( '.resize_handle').disableSelection();
 $(".ui-resizable-handle", '.edit').live("mouseenter mousedown click", function(event) {
	 
	 if ( event.type == "mouseenter" ) {
	 if( window.mw_drag_started == true){
	//	 window.mw_drag_started = true;
	  mw_remove_editables();
		
	 
	 }
	 }
	 
	 
	  if ( event.type == "mouseout" ) {
		  if( window.mw_drag_started == true){
		  //window.mw_drag_started = false;
		  }
	 }
	 
	 
	 if ( event.type == "mousedown" ) {
		// event.preventDefault(); // this prevents the original href of the link from being opened
		//	event.stopPropagation(); // this prevents the click from triggering click events up the DOM from this element
		 window.mw_drag_started = true;
		  mw_remove_editables();
  //return false;
	 }
	 
	 if ( event.type == "mouseup" ) {
			// event.preventDefault(); // this prevents the original href of the link from being opened
			//	event.stopPropagation(); // this prevents the click from triggering click events up the DOM from this element
			mw_drag_started_false();;
		 
	  //return false;
		 }
	 
	 
	 if ( event.type == "click" ) {
		// event.preventDefault(); // this prevents the original href of the link from being opened
		//	event.stopPropagation(); // this prevents the click from triggering click events up the DOM from this element
				 
				
  //return false;
	 }
	 

	 
	 
	 
	 
 });
 
 
$(".col", '.edit').live("mouseenter mouseleave", function(event) {
	 
	 if ( event.type == "mouseenter" ) {
		  
			if( window.mw_drag_started == false){
				
				 // window.mw_sortables_created = false;
				//  init_edits()
				
		  if (window.console != undefined) {
				console.log('mouseenter on col ' );	
			}
		  
		  
			$module_title = 	$(this).attr('module_title');
		//  $( this ).children('.mw_mod_drag_handle').show();
	   
			if( $( this ).children('.col_handle').length || $( this ).parents('.col_handle').length){  
				 
			} else {
				$( this ).prepend('<div class="col_handle">column</div>');
			
			}
			
			if( $( this ).children('.resize_handle').length || $( this ).parents('.resize_handle').length){  
				 
			} else {
				//$( this ).prepend('<span class="resize_handle ui-resizable-handle ui-resizable-w"></span>');
			
			}
			
			mw_set_handles()
			
			
			
			
		   $( this ).children('.col_handle').show();
		   if( window.mw_editing_started == false){
			//var offset = $(this).offset();
			//position1223 = $(this).position();
		//	mw_set_handles()
			//  $( this ).children('.col_handle').css({top:position1223.top,left:position1223.left+35});
		   }
		   
		//	  $( this ).removeClass('ui-resizable-resizing');
			
			  
			  
		  // $(this ).resizable('destroy');
 	 $(this).resizable({
				 handles: 'e',
			//	 handles: {'w': '.resize_handle'},
			//	 ne:'span.resize_handle', se:'div.wnd_bottom_right', sw:'div.wnd_bottom_left', nw:'div.wnd_top_left' ,
			//	 cancel: '*:not(.resize_handle)',
		//		 autoHide: true ,
				  resize: function(event, ui) {
					
					 
				 },
				 
				 start: function(event, ui) {
			//		 window.mw_drag_started = true;
				// window.mw_handle_hover = true;

				// $(this).next(".col").css('background-color', 'red');
				 //$(this).next(".col").attr('also_resize', window.mw_last_hover);
				 
		//		 $(this).not('img').css("height", 'auto');
				 
				 
			},
			
			
			//alsoResizeReverse: "[also_resize='"+window.mw_last_hover+"']",
			 stop: function(event, ui) {
 
		        $(this).not('img').css("height", 'auto');
		        $(this).css("position", '');
		        $(this).css("top", '');
		        $(this).css("left", '');
		        window.mw_drag_started = false;
		        window.mw_drag_started = false;
			//	setTimeout("   window.mw_drag_started = false;",500);
			} 
				 
			 
			});
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
		   //init_edits()
			} 

		  
	  }
	 if ( event.type == "mouseleave" ) {
			setTimeout("mw_drag_started_false()",1000);
		 $(this ).resizable('destroy');
	 }
	 
	 
	 
	 
	 
	 
});

function mw_drag_started_false(){
	window.mw_drag_started = false;

}

function mw_set_grid(){
	
    
    

	
	
}

var copyHelper= null;
function mw_sidebar_make_sortables(){
	var copyHelper= null;
	
  $(".sortable_modules").sortable('destroy');
	
	$(".sortable_modules").sortable({
	//connectWith: '.edit',
	items: '.module_draggable',
	forcePlaceholderSize: true,
	tolerance: 'pointer',
	zIndex: 50000,
	forcePlaceholderSize: true,
	forceHelperSize : true ,
	tolerance: 'intersect',
  //
	cancel: '.mw_mod_wrap',
	placeholder: "to_here_drop",
 
	
	
	helper: function(e,li) {
	copyHelper= li.clone().insertAfter(li);
	return li.clone();
	},	start: function() {
 
 		//$( '.edit').append('<div class="mw_dropable_generated"></div>');
 	//  	$( '.edit').prepend('<div class="mw_dropable_generated"></div>');
// 
	 	
	 //	$('<div class="mw_dropable_generated"></div>').insertAfter('.container');
	//	$('<div class="mw_dropable_generated"></div>').insertBefore('.container');
	 	
	 
		window.mw_drag_started = true;
	},
	receive: function() {
		setTimeout("set_drag_started_false()",500);
		
	},
	//placeholder: "ui-state-highlight",
	stop: function() {
		window.mw_sortables_created = false;
		mw.saveALL();
		//window.mw_drag_started = false;
	  	//setTimeout("set_drag_started_false()",500);
		copyHelper
//	copyHelper && copyHelper.remove();
	}
	}).sortable( "option", "connectWith", ".edit" ).disableSelection();
//	$(".sortable_modules","#mw_sidebar_design_holder").sortable('option,"cancel",".mw_mod_wrap, .col, .container"');
	
	
	
	
	
	
	
	
	
	
	 
	
	
	
	
	
}
function mw_remove_inner_sortables(){
	
//	$( ".module" ).disableSelection();
	//$( ".edit .module *" ).disableSelection();
	//$( ".edit .module > *" ).disableSelection();
//	$( ".mw_mod_wrap" ).sortable( "destroy" )
//	$( ".edit input" ).disableSelection();
	//$( ".edit .module > *" ).sortable('destroy');
	//$( ".edit .module *" ).sortable('destroy');
	//$( ".edit .module > *" ).sortable( "option", "disabled", true );
//	$( ".edit .module *" ).dropable( "option", "disabled", true );
}






var mw_click_on_history = function(){
	$which =  $('.mw_history_file_active:last').attr('rel');

   replace_content_from_history($which)
   
 $is_last = $('.mw_history_file_active').next().length ;
 $is_first = $('.mw_history_file_active').prev().length;
  // alert($is_last);
   if($is_last ==0){
	   $('.mw_history_next').fadeOut();
	   $('.mw_history_prev').fadeIn();
	   
   } 
   
     if($is_first ==0){
	   $('.mw_history_next').fadeIn();
	   $('.mw_history_prev').fadeOut();
	   
   } 
   
   
}
var mw_click_on_history_next= function($direction){
	
  // var $toHighlight = $('.mw_history_file_active').prev().length > 0 ? $('.mw_history_file_active').prev() : $('#mw_history_files li').last();
   var $toHighlight = $('.mw_history_file_active').prev().length > 0 ? $('.mw_history_file_active').prev() : $('#mw_history_files li').first();
            
			if($toHighlight != false){
			$('.mw_history_file_active').removeClass('mw_history_file_active');
            $toHighlight.addClass('mw_history_file_active');
   
   mw_click_on_history();
			}
   
}

var  mw_click_on_history_prev  = function($direction){
	
  // var $toHighlight = $('.mw_history_file_active').next().length > 0 ? $('.mw_history_file_active').next() : $('#mw_history_files li').first();
    var $toHighlight = $('.mw_history_file_active').next().length > 0 ? $('.mw_history_file_active').next() : $('#mw_history_files li').last();
          
		if($toHighlight != false){  
		  $('.mw_history_file_active').removeClass('mw_history_file_active');
            $toHighlight.addClass('mw_history_file_active');
			
			
   
    mw_click_on_history();
	}
}


function gEBI(id) {
    return document.getElementById(id);
}
 
 
 

var editing;
var c;
var guid;
 
 
 

function load_content(g)
{
    $.get("load_content", {id:g}, function(x){done_load(x)}, 'json'); 
}

function done_save(x)
{
    done_load(x);
    $("#versions").html(x.versions);
    
}

function done_load(x)
{
    $("#header").html("guid=" + x.guid + " last modified=" + x.mod
		      + " editable=" + x.edit);
    guid = x.guid;
    $("#content").html(x.val);
    switch(x.edit){
    case true:
	set_edit_on();
	break;
    case false:
	set_edit_off();
	break;
    };
}

 


function load_content_old(type, G)
{
    // alert("load content type=" + type + " guid=" + G);
    // $("#header").html("Guid=" + G);
    $("#content").load("load_content",{id:G});
    guid=G;
    switch(type){
    case "edit":
	set_edit_on();
	break;
    case "lock":
	set_edit_off();
	break;
    default:
	alert("????????");
    };}

 

function toggle_class(x)
{
    $(x).toggle();
}
 

function add_regular_button(x, text, callback)
{
    var button = $("<button>" + text + "</button>").
	click(callback);
    x.append(button);
}  

function add_div(x)
{
    var y = "." + x;
    $(x).show();
    document.execCommand("insertHTML",
			 false,
			 "<div class='" + x +"'>Add " + x + "here</div>");
}

function add_edit_button(x, text, cmd, val)
{
    var button = $("<button>" + text + "</button>").
	click(function(){
		
		if(cmd == "insertHTML"){
			if($.browser.msie){
				document.selection.createRange().pasteHTML(val)
			}else{
				document.execCommand(cmd, false, val);
			}
		} else {


		document.execCommand(cmd, false, val);
		}
		
	    });
    x.append(button);
}



var cssApplier;
$(document).ready(function() {
	mw_remove_editables()
	 rangy.init();
	 //   cssApplier = rangy.createCssClassApplier("red", true);


	 
	       // rangy.init();
	      //  cssApplier = rangy.createCssClassApplier("blue", true);
	 
	
	//document.execCommand("styleWithCSS",false,false);
	if( window.mw_editables_on_page == undefined){
		window.mw_editables_on_page  = $( ".edit" );
	}
	
	if(window.myNicEditor == undefined){
	/*	  myNicEditor = new nicEditor({

		            fullPanel : true,
		            iconsPath : "<?php print( ADMIN_STATIC_FILES_URL);  ?>js/nicEditorIcons.gif",
		  		  uploadURI : "<? print site_url('api/media/nic_upload') ?>",
		            onSave:function(content, id, instance){

		  nic_save(content, id, instance);


		            }

		  });*/
		myNicEditor = false;
		  window.myNicEditor =myNicEditor;
		} else {
			myNicEditor = window.myNicEditor;	
		}
	
	
	//myNicEditor.setPanel('mw_editbar');
	
	
	 $('.edit[contentEditable=true]').keydown(function(e) {
		 if( window.mw_editing_started == true &&  window.mw_drag_started == false){
		    // trap the return key being pressed
		    if (e.keyCode == 13) {
		    	
		    	
		    	
		    	if (document.all) {
		    		var oRng = this.contentWindow.document.selection.createRange( );
		    		oRng.pasteHTML('<br /><br />');
		    		oRng.collapse(false);
		    		oRng.select();
		    		} else {
		    		document.getElementById(rteName).contentWindow.document.execCommand('insertHTML', false, '<br /><br />');
		    		}
		    	
		    	
		    	
		   
		    	
		    	
		    	
		    	
		    	
		    	
		      // insert 2 br tags (if only one br tag is inserted the cursor won't go to the second line)
		 //    document.execCommand('insertHTML', false, '<br /><br />');
		      // prevent the default behaviour of return key pressed
		      return false;
		    }
		 }
		  });

	
	//init_edits()
	 
	
//	$(document).bind('keydown', 'ctrl+z', mw_click_on_history_prev);
	//$(document).bind('keydown', 'ctrl+y', mw_click_on_history_next);
	//$(document).bind('keydown', 'ctrl+s', mw.saveALL);
	
	
	mw_resize_admin_sidebar();
 
	if(typeof window.parent.mw_edit_init == 'function'){
	window.parent.mw_edit_init(window.location.href);
	}

	
	
	
 
	
	

	
	

  
                 


 

	


 

$(window).load(function(){
	 // init_edits()


});


  
	}); // end doc ready

var $curent_edit_element_id

function mw_delete_module_by_id($id, $ask){
	$del = true;
	if($ask != undefined){
	
	var r=confirm("Are you sure you want to delete this module?");
	if (r==true)
	  {
		$del = true;
	  }
	else
	  {
		$del = false;
	  }
	} else {
		$del = true;
	}
	
	
	
	
	if ($del==true){
	  var $module_by_id  =   $id;
      //alert($module_by_id); 
       $module_by_id  =$("div[module_id='"+$module_by_id+"']"); 
      $module_by_id.remove();
	}
}
window.mw_modules_info_cache = new Array();

function load_edit_module_by_module_id($the_module_id) {
	// mw_remove_editables();
	// mw_make_draggables();
	if($the_module_id == undefined){
		
	} else {
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
		   module_name_original = module_name;
	   module_name =   module_name.replace("/", "_mw_slash_replace_")
	   }
	   
	   
	   
	   
	   
	   
	//   $m_info = $('#module_focus_holder_id')
	   
 
		 // alert(window.mw_modules_info_cache[id] );

	   
		 
		 if (edit ==undefined ) {
		 var edit = $module_by_id.parents(".module:first").attr("edit");
		 }
		 
		// alert(edit);
		 if (edit !=undefined ) {
			 
			
 
		 	 page_id = $("#mw_edit_page_id").val();
			 post_id = $("#mw_edit_post_id").val();
			 category_id = $("#mw_edit_category_id").val();
		 
			 
	   $url = '<? print site_url("api/module") ?>/admin:true/base64:'+edit+'/page_id:'+page_id+'/post_id:'+post_id+'/category_id:'+category_id+'/'+'module_to_edit:'+module_name+'/';
	  // alert(id);
	   if(id != undefined){
	// alert(id);
		   window.mw_last_hover++;
	
			 $("#style_mw_id").val( id);
			 $("#mw_css_editor_element_id").val(id);
			 $("#style_mw_tag").val( 'module');
			  
	 
			 
			 
			
	 
		//	mw.outline.init("div[module_id='"+$the_module_id+"']", '#DCCC01');
		 

			  
		// $("div[module_id='"+$the_module_id+"']").disableSelection();
		   
		   $('#mw_edit_module_iframe_'+id).show();
		  // call_edit_module_ajax($url, id, module_name_original);
		   call_edit_module_ajax(id, module_name_original);
		   mw_resize_admin_sidebar()
	   }  
	  


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
		  dataType: "json",
		  success: function(data) {
			 //  $("#"+$id).html(data);
			 // var item = jQuery.parseJSON(data)
			    $.each(data, function(i, d) {

			    	$("#"+this.page_element_id).html(this.page_element_content);

			    });

init_edits()




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



function dummy_request(){
	
	   http = new XMLHttpRequest();

	   http.open("GET", "<? print site_url('module/text') ; ?>");
	   http.onreadystatechange=function() {
	       if(http.readyState == 4) {
	        //    alert(http.responseText);
	       }
	   }
	   http.send(null);
}

function call_edit_module_ajax(id, module_name_original) {
				 if(id != undefined){
					//url = url + 'element_id:'+id;
					$curent_edit_element_id=id;
				 }
				 
				 
				 
				 
				 //$url = '<? print site_url("api/module") ?>/:'+edit+'/page_id:'+page_id+'/post_id:'+post_id+'/category_id:'+category_id+'/'+'module_to_edit:'+module_name+'/';
				
				 
				
				 
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
					 
					// module_name_original
					 $('<div>', {
						 'class': 'mw_edit_module_iframe',
						    id:   'mw_edit_module_settings_'+id 
						     
						   
						}).appendTo('#mw_sidebar_module_edit_holder');
					 
					 
				
					 
					 
					 $('<div>', {
						  
						  'class': 'mw_edit_module_settings_header',
						    id:   'mw_edit_module_info_'+id
						   
						   
						}).appendTo('#mw_edit_module_settings_'+id);
					 
					 
					 $('<div />', {
						    name:  'mw_edit_module_iframe_'+id,
						 // className: 'mw_edit_module_iframe',
						    'class': 'mw_edit_module_settings_iframe',
						    id:   'mw_edit_module_iframe_'+id
						    //borderWidth :0,
						   // src:  url
						   
						}).appendTo('#mw_edit_module_settings_'+id);
					 document.getElementById('mw_edit_module_iframe_'+id).style.borderWidth = 0;
					 document.getElementById('mw_edit_module_iframe_'+id).setAttribute('frameborder', 0);
					 
					 
					 data1 = {}
					   data1.module = 'admin/mics/module_info';
					    data1.module_info = module_name_original;
					    data1.module_id = id;
					   $.ajax({
					  url: '<? print site_url('api/module') ?>',
					   type: "POST",
					      data: data1,
				
					      async:true,
				
					  success: function(resp) { 
				
					   $('#mw_edit_module_settings_'+id).prepend(resp);
					   
					  
					   
					   
					  // $('#mw_edit_module_settings_'+id).append('<a class="mw_edit_module_style_btn" href=\'javascript:mw_html_tag_editor(\"'+1+'\")\'>Style module</a>');
 
					 

					  }
					    });
					   
					 	 page_id = $("#mw_edit_page_id").val();
						 post_id = $("#mw_edit_post_id").val();
						 category_id = $("#mw_edit_category_id").val();
				 
						//	data1 = {}
						 var $module_by_id  =$("div[module_id='"+$curent_edit_element_id+"']"); 
						 
						 
						 data1 =  $module_by_id.getAttributes();
						// alert(data1);
						   data1.module = 'admin/'+module_name_original;
						   data1.page_id =page_id;
						   data1.post_id = post_id
						   delete    data1.mw_params_module ;
						   
						   data1.category_id =category_id;
						   data1.element_id = id;
						   data1.module_id = id;
						// data1.type =  $("#media_type").val();
							
						  $('#mw_edit_module_iframe_'+id).load('<? print site_url('api/module') ?>',data1);
					   
				//		data1 = {}
				//		   data1.module = 'admin/'+module_name_original;
				//		   data1.page_id = '<? print intval(PAGE_ID) ?>';
				//		   data1.post_id = '<? print intval(POST_ID) ?>';
				//		   
				//		   data1.category_id = '<? print intval(CATEGORY_ID) ?>';
				//		   data1.element_id = id;
				//		   data1.module_id = id;
				//		  
				//		   $.ajax({
				//		  url: "<? print site_url('api/module') ?>",
				//		   type: "POST",
				//		      data: data1,
				//
				//		      async:false,
				//
				//		  success: function(resp) {
				//
				//			   $('#mw_edit_module_iframe_'+id).html(resp);
				//			   
				//			   
				//			   dummy_request()
				//		
				//			   
				//
				//		  }
				//		    }); 
					   
					   
						  var linkzzz = document.createElement('a');
						   linkzzz.setAttribute('href', 'javascript:mw_html_tag_editor("'+id+'")');
						   linkzzz.setAttribute('class', 'mw_edit_module_style_btn');
						   linkzzz.innerHTML = "Click here to style module box";
						  
						   $a = gEBI('mw_edit_module_settings_'+id);
						   $a.appendChild(linkzzz);
					 
					 
					   $('#mw_edit_module_settings_'+id ).show();
					 
					 $('#mw_edit_module_settings_'+id).addClass('mw_edit_module_iframe');
					} else {
						// $('#mw_edit_module_iframe_'+id).attr("src") =url;
						 //$('#mw_edit_module_iframe_'+id).show();
						$('#mw_edit_module_settings_'+id ).show();
						
					}
				
				 
				/*
				 * var height=window.innerWidth;//Firefox if (document.body.clientHeight) {
				 * height=document.body.clientHeight;//IE } //resize the iframe according to the
				 * size of the //window (all these should be on the same line)
				 * document.getElementById('mw_edit_module_iframe_'+id).style.height=parseInt(height-document.getElementById('admin_sidebar').offsetTop-8)+"px";
				 */
				 
				 //$('#mw_edit_module_iframe_'+id).height($("#admin_sidebar").height());
					
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


 

function call_edit_module_iframe(url, id, module_name_original) {
			 if(id != undefined){
				url = url + 'element_id:'+id;
				$curent_edit_element_id=id;
			 }
		
		 $el = document.getElementById('mw_edit_module_iframe_'+id);
		 mw_sidebar_nav('#mw_sidebar_module_edit_holder');
		 $(".mw_edit_module_iframe").hide();
		 if ( $el == undefined){
		
			 $('<div>', {
				 className: 'mw_edit_module_iframe',
				    id:   'mw_edit_module_settings_'+id 
				     
				   
				}).appendTo('#mw_sidebar_module_edit_holder');
			 
			 
		
			 
			 
			 $('<div>', {
				  
				  className: 'mw_edit_module_settings_header',
				    id:   'mw_edit_module_info_'+id
				   
				   
				}).appendTo('#mw_edit_module_settings_'+id);
			 
			 
			 $('<iframe />', {
				    name:  'mw_edit_module_iframe_'+id,
				 // className: 'mw_edit_module_iframe',
				    className: 'mw_edit_module_settings_iframe',
				    id:   'mw_edit_module_iframe_'+id,
				    borderWidth :0,
				    src:  url
				   
				}).appendTo('#mw_edit_module_settings_'+id);
			 document.getElementById('mw_edit_module_iframe_'+id).style.borderWidth = 0;
			 document.getElementById('mw_edit_module_iframe_'+id).setAttribute('frameborder', 0);
			 
			 
			 data1 = {}
			   data1.module = 'admin/mics/module_info';
			    data1.module_info = module_name_original;
			   $.ajax({
			  url: '<? print site_url('api/module') ?>',
			   type: "POST",
			      data: data1,
		
			      async:true,
		
			  success: function(resp) {
		
			   $('#mw_edit_module_settings_'+id).prepend(resp);
		
			 
		
			  }
			    });
			 
			 
			   $('#mw_edit_module_settings_'+id ).show();
			 
			 $('#mw_edit_module_settings_'+id).addClass('mw_edit_module_iframe');
			} else {
				// $('#mw_edit_module_iframe_'+id).attr("src") =url;
				 //$('#mw_edit_module_iframe_'+id).show();
				$('#mw_edit_module_settings_'+id ).show();
				
			}
		
		 
			
		 $('#mw_edit_module_iframe_'+id).show();

}


function update_module_element($new_value) {
	
	$temp = $('#'+$curent_edit_element_id).parents(".editblock") .attr("id")
	// alert($temp);
	  $('#'+$curent_edit_element_id).attr("mw_params_encoded", $new_value);
	// save_editblock($temp);

	  // mw.modal.close();
	mw.saveALL();
	  
}


function mw_saveALL($no_async) {
	
 
	mw.saveALL($no_async);
	  
}



mw.saveALL = function($no_async){
 //	$( ".edit .module" ).children().remove();
	$( ".edit .mw_mod_drag_handle" ).remove();
	$( ".edit .col_handle" ).remove();
	$( ".edit .container_handle" ).remove();
	$( ".edit .row_handle" ).remove();
	$( ".js_remove" ).remove();
	$( ".module script", '.edit' ).html('Saving... please wait...');
	
	 $(".js_remove_empty").each(function() { 
			
		    if($(this).children().length < 1) {
		    	$(this).remove() 
		 
		    }

		});
	 
	
	
	
	
	
	
	
	window.no_async = true;
	nic_save_all(function(){
	
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