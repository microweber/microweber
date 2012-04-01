<? 
 
 $exit_l_ed = url_param_unset('layout_editor',url());
 $enter_l_ed = CI::model ( 'core' )->urlConstruct(url(), array('layout_editor' => 'yes'));
  $exit_live_edit = CI::model ( 'core' )->urlConstruct(url(), array('?editmode' => 'n'));

// var_dump($exit_l_ed , $enter_l_ed);
 ?>
<script type="text/javascript">
  merc_src = "<?php   print( ADMIN_STATIC_FILES_URL);  ?>mercury";
   admin_panel = "<?php   print( ADMIN_URL);  ?>/";
   //alert(admin_panel);
   
   
 </script>
<link href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>mercury/stylesheets/mercury.bundle.css" media="screen" rel="stylesheet" type="text/css"/>
<!--<link rel="stylesheet" type="text/css" href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>jquery/jquery-ui-1.8.13.custom/css/custom-theme/jquery-ui-1.8.13.custom.css"/>
-->

<? if(url_param('layout_editor') != 'yes'): ?>
<script src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>mercury/javascripts/mercury_loader.js?src=mercury&pack=bundled" type="text/javascript"></script>

<? else : ?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>

<!--<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.17/themes/base/jquery-ui.css"  rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>jquery/jquery-ui-1.8.13.custom/js/jquery-ui-1.8.13.custom.min.js"></script>-->

<script type="text/javascript">
  
  
  $(document).ready(function() {
init_sortables()
 
	});

		
      
    </script>
<? endif; ?>
<script src="https://github.com/brandonaaron/livequery/raw/master/jquery.livequery.js" type="text/javascript"></script>

<!--<link rel="stylesheet" type="text/css" href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>bootstrap/css/bootstrap.css"/>
<script type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>bootstrap/js/bootstrap.js"></script>
-->


<!--   <script src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>mercury/javascripts/mercury.js" type="text/javascript"></script>
              <script src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>mercury/javascripts/mercury_dialogs.js" type="text/javascript"></script>
-->
<script type="text/javascript">

function openKCFinder(field) {
    window.KCFinder = {
        callBack: function(url) {
            window.KCFinder = null;
            //field.value = url;
			$('.url_finder').val(url);
			  $("#mercury_iframe").contents().find(".url_finder").val(url);
			  if(field != undefined){
				$(field).val(url);
				 $("#mercury_iframe").contents().find(field).val(url);
			  }
			  
			  
        }
    };
    window.open('<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/tiny_mce/plugins/kcfinder/browse.php?type=images&dir=images/public&custom=true', 'kcfinder_textbox',
        'status=0, toolbar=0, location=0, menubar=0, directories=0, ' +
        'resizable=1, scrollbars=0, width=800, height=600'
    );
}


function mw_load_history_module(){
	
	
	data1 = {}
   data1.module = 'admin/mics/edit_block_history';
   
   
   data1.page_id = '<? print intval(PAGE_ID) ?>';
   data1.post_id = '<? print intval(POST_ID) ?>';
   data1.category_id = '<? print intval(CATEGORY_ID) ?>';
   data1.for_url = document.location.href;
   
   
    
   
 //  alert(data1);
  //$("#mercury_iframe").contents().find(".url_finder")
 // $(".mercury-toolbar-container").contents().find(".mercury-panel-pane").load('<? print site_url('api/module') ?>',data1);
 parent.$('.mercury-history-panel').load('<? print site_url('api/module') ?>',data1);
/*   $.ajax({
  url: "<? print site_url('api/module') ?>",
   type: "POST",
      data: data1,

      async:true,

  success: function(resp) {
parent.$(".mercury-toolbar-container").contents().find(".mercury-history-panel").html(resp);
    
 



  }
    }); */
	 
	
}
</script> 
 <script type="text/javascript">
 function content_url_finder($kw, $category_id){
   
   
   
   data1 = {}
   data1.module = 'posts/list';
   data1.include_pages = '1';
   data1.read_more_link_text = 'Select';
   
   
   if(($kw == false) || ($kw == '') || ($kw == undefined)){
	$kw = null;  
	
   } else {
	data1.keyword = $kw;
	data1.curent_page = 1;
	data1.items_per_page = 1000;
	
   }
   
   
     if(($category_id == false) || ($category_id == '') || ($category_id == undefined)){
	//$category_id = null;   
   } else {
	  // data1.category = $category_id;
   }
   
   
   
   
   
   
   $.ajax({
  url: '<? print site_url('api/module') ?>',
   type: "POST",
      data: data1,

      async:true,

  success: function(resp) {
 
   $('.mw-finder-content').html(resp);
   
	
	
	
	
	//$('#results_holder_title').html("Search results for: "+ $kw);


  }
    });
   
 
}
    
   $(document).ready(function() {
    
	 
 
 
  
	
	
	
	
 
	   
	   $("#link_external_url").live("keyup", function(){
			$viz =  $('.mw_finder_list').is(":visible");
			if($viz == true){
			$kwv = $(this).val();								  
  $('.mw_finder_list').html("<div class='mw-finder-content'></div>");
  content_url_finder($kwv);
			 }
});
	   
	 
	 $(".mw-finder-content a").live("click", function(e){
	  $(this).hide();	
	  $l = $(this).attr('href');	
	 
	   $("#link_external_url").val($l);
	    $(".mw-finder-content").remove();
	  e.stopPropagation(); 
 e.preventDefault();
  
		});
	   
	   
 



});

 


   </script>
<div id="kcfinder_div"></div>
<div id="parentContainer">
    <div id="nonresizable_IMGS"></div>
    <div id="resizable_IMGS"></div>
</div>
<script type="text/javascript">
    <!-- The sidebar event delegation is not registered "here"... -->
    $(document).ready(function () {
      $('.mw-snippets-panel').live('mouseenter', function () {
													  
													  $('.mw-snippets-panel').find(".snippet_draggable").each(function(j){
								   
								   
								   $val = $(this).attr('data-snippet');
								   $val = $val + '0';
								   $(this).attr('data-snippet', $val );
								//   $("#mercury_iframe.edit").each(function(j){


 //$(this).addClass("mw_edited");
														   // $(this).fadeOut().fadeIn();;
														    });
         
      });
    });
  </script>
<style>
	
	
	.ui-resizable-helper { border: 2px dotted #00F; }
	</style>
<script type="text/javascript">
 
$(document).ready(function(){
$(".mw_option_field").live("change blur", function(){

 
	 //alert('Handler for .change() called.');
	//<? print site_url('api/content/save_option') ?>
	//var refresh_modules11 =  $(this).attr('name');
//	alert(refresh_modules11);
	
	
	
	
	
	 var refresh_modules11 =  this.getAttribute("refresh_modules");
	// var refresh_modules11 =   $(this).attr('refresh_modules')
	// alert(refresh_modules11);
	
	
	
	
	$.ajax({
		  
		  type: "POST",
		   url: "<? print site_url('api/content/save_option') ?>",
		   data: ({
			   
			   option_key : $(this).attr('name'),
			   option_group : $(this).attr('option_group'),
			   option_value : $(this).val()
			   
		   
		   }),


		  success: function(){
			  
			  
			  if(window.mw != undefined){
		if(window.mw.reload_module != undefined){
		mw.reload_module($(this).attr('option_group'));
		
		}
			  }
		
		if(refresh_modules11 != undefined && refresh_modules11 != ''){
			refresh_modules11 = refresh_modules11.toString()
			
//alert(refresh_modules11);
  if(window.mw != undefined){
			if(window.mw.reload_module != undefined){
				window.mw.reload_module(refresh_modules11);
			}
  }
			  if(parent.mw != undefined){
			if(parent.mw.reload_module != undefined){
				parent.mw.reload_module(refresh_modules11);
			}
			  }
		/*		*/
			
			
			
			
			
			
			
		}
		
		  //  $(this).addClass("done");
		  }
		});
	
	
	
	});
});

$(document).ready(function() {
	//	$('.edit').aloha();
		
		 <? if(url_param('layout_editor') != 'yes'): ?>
		$('.mw_layout').addClass('edit');
		$('.edit').addClass('mercury-region');
		$('.edit').attr("data-type",'editable');
		$('div[rel="layout"]').removeClass("mercury-region");
		<? else: ?>
		//$('div[rel="layout"]').addClass('edit');
		$('div[rel="layout"]').attr("data-type",'editable');
 
		<? endif; ?>
 		
		
 

		
 
	});
function load_new_layout_elements(){
	
	$('div[rel="layout"]').find('.mw_load_element').each(function(index) {
   $el_f = $(this).attr("element");
   if($el_f != ''){
     urlz1= '<? print site_url('api/content/load_layout_element') ?>' ;
										 $(this).load(urlz1, {element: $el_f},function() {
											$el_f = $(this).attr("element", '');
										 }); 
										 
   }
   // alert(index + ': ' +$el_f );
});
	
	
	   init_sortables()
	
}
function init_sortables(){
	       // $('#mercury_iframe').contents().find('.edit').html('Hey, i`ve changed content of  body>! Yay!!!');
		   
			
			
			
			
			$('div[rel="layout"],.mw_load_elements').sortable('destroy');
			$('div[rel="layout"],.mw_load_elements').sortable({ 
				 items: '.row' ,
			 handle: '.row' ,
			//  cancel: '.col' ,
			  handle: '.row, a, .row > *' ,
			// connectWith: "*[rel=\"layout\"], .col > *",
			 connectWith: "*[rel=\"layout\"]",
			  forceHelperSize: true,
			  forcePlaceholderSize: true,
			  
			    update: function(event, ui) {
  
  load_new_layout_elements()
  
  
  			 }
			 
			 
   
   
									 
			});
			
			
			$('.edit .row').sortable('destroy');
			 $('.edit .row').sortable({ 
			//  appendTo: '.row' ,											   
			 items: '.col' ,
			   
			 handle: '.col, a, .box, .col > *' ,
			 //  containment: '.row',
			//   containment: 'parent',
			//  cancel: '.col > *' ,
			 forceHelperSize: true,
			  iframeFix: true,
		//	  connectWith: ".row",
			 forcePlaceholderSize: true
			
			 }).disableSelection();
			 
		/*	 $('.edit').find('.col').resizable('destroy');
			$('.edit').find('.col').resizable({
			ghost: true,
			helper: "ui-resizable-helper" 
		});
			
			$('.edit').find('.row').resizable('destroy');
			$('.edit').find('.row').resizable({
			ghost: true,
			  alsoResize: '#'+$(this).attr('id')+' *',
			helper: "ui-resizable-helper" 
		});*/
			
			
			
			// jQuery('#mercury_iframe').contents().find( "#sortable" ).sortable( { forceHelperSize: true,iframeFix: true});
	//	jQuery('#mercury_iframe').contents().find( "#sortable" ).disableSelection();
			 
			 
/*		   jQuery('#mercury_iframe').contents().find('.row').sortable('destroy');
			 jQuery('#mercury_iframe').contents().find('.row').sortable({ 
			  appendTo: '.row' ,											   
			 items: '.col' ,
			 handle: '.col, a, .box, .col > *' ,
			   containment: '.row',
			//  cancel: '.col > *' ,
			 forceHelperSize: true,
			  iframeFix: true,
			 forcePlaceholderSize: true
			
			 }).disableSelection();*/
		 


 


}

 

function mercuryLoaded(){
	 window.Mercury = top.Mercury;
    //  Mercury.trigger('initialize:frame');
	 // alert(1);
	 $('#mercury_iframe').load(function(){
		//	init_sortables()							
    });

 }


jQuery(window).live('mercury:ready', function() {
											  
											     
	  
	  
   
 
  
		
 
 //  jQuery("#mercury_iframe").contents().find( ".row" ).disableSelection();
  //$('.edit').addClass('row');
  
  
  
  
  
});

jQuery(window).keypress(function(event) {
  if ((event.which == 115 && event.ctrlKey)){
	  
	  
	  if (top.Mercury) {
  top.Mercury.trigger('mercury:action', {action: 'save'});
   top.Mercury.trigger('save')
  //alert("Ctrl+S pressed, saving");
}


      //
	 // jQuery(top).trigger('mercury:action', {action: 'save'});
//Mercury.trigger('save')

	 // Mercury.trigger('mercury:action', {action: 'save'});
      event.preventDefault();
  }
});

function mw_save_all(){
nic_save_all();

}


 nic_save_all = function(callback, only_preview){
$(".mw_non_sortable", '.edit').removeClass('mw_non_sortable');





 var master = {}
  // $(".mw_edited").each(function(j){
								   
								   
								   
								   
								   
<? if(url_param('layout_editor') != 'yes'): ?>
 jQuery("#mercury_iframe").contents().find(".mercury-region").each(function(j){
<? else : ?> 
$('div[rel="layout"]').each(function(j){
<? endif; ?>
								   
								 
								   
								   
								   
								//   $("#mercury_iframe.edit").each(function(j){


 //$(this).addClass("mw_edited");

var nic_obj = {};
if(window.no_async == true){
$async_save = false;	
	window.no_async = false;
} else {
	$async_save = true;	
}





 

var attrs = $(this).get(0).attributes;
for(var i=0;i<attrs.length;i++) {
    temp1 = attrs[i].nodeName;
    temp2 = attrs[i].nodeValue;

      if((temp2!=null) && (temp1 != null) && (temp1 != undefined) && (temp2 != undefined)){

        if((new String(temp2).indexOf("function(") == -1)&& (temp2 !="")  && (temp1 != "")){
          nic_obj[temp1] =temp2;
      }
    }

}
content = $(this).html();

var obj = {
    attributes:nic_obj,
    html : content
}
var objX = "field_data_"+j;
if(master[objX] == undefined){
master[objX] = obj;
}

		
 


   });

		
		
		
			//  mw.modal.overlay();
		
	$emp =  false;
		if ($emp == true){
		 
			
		} else {
		


master_prev = master;
//master_prev['mw_preview_only'] = 1;

   	$.ajax({
		  type: 'POST',
		  url: "<?php print site_url('api/content/save_field');  ?>",
		  data: master,
		  datatype: "json",
          async:false,
		  beforeSend :  function() {
			
			  window.saving =true;
			 // $( "#ContentSave" ).fadeOut();
		 
		  },
		  success: function(data) {
			  
			  mw_load_history_module();
			  			   
if (window.console != undefined) {
	var myJSONText = JSON.stringify(master, '|||||');
	//console.log('Saving ' + myJSONText);
}
	
			  
			  
			  window.saving =false;
				  $( "#ContentSave" ).fadeIn();
				 //   $( ".module_draggable" ).draggable( "option", "disabled", false );
				   window.mw_sortables_created = false;
					  window.mw_drag_started = false;
					   
			 
				 
				 if(only_preview  == undefined || only_preview  == false){
				 jQuery.each(data, function(i, item) {
								
								
								<? if(url_param('layout_editor') != 'yes'): ?>
								jQuery("#"+data[i].page_element_id).html(data[i].page_element_content);
								<?  endif; ?>
								
								
								//		alert(item.page_element_id+item.page_element_content);
					//$("#"+data[i].page_element_id).html(data[i].page_element_content);
					 
				});
				  
	 
				 
				 }
				 
				 //callback.call(this);
				 
 
			  
			  }
		})
	
		}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	

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

</script>
<script type="text/javascript"> 
 

 


function replace_content_from_history($history_file_base64_encoded){
	
	<? if($params['tag'] != 'edit') : ?>
  // load_editblock('<? print $id ?>', $history_file_base64_encoded) ;
    load_field_from_history_file('<? print $id ?>', $history_file_base64_encoded) ;
   <? else: ?>
   
   load_field_from_history_file('<? print $id ?>', $history_file_base64_encoded) ;
   <? endif; ?>
   
   
}

function load_field_from_history_file($id, $base64fle){

if($id != undefined && $base64fle != undefined){
 
	$.ajax({
		  type: 'POST',
		  url: '<? print site_url("api/content/load_history_file") ?>',
		  data: { history_file: $base64fle },
		  dataType: "json",
		  success: function(data) {
			 //  $("#"+$id).html(data); 
			 // var item = jQuery.parseJSON(data)
			    $.each(data, function(i, d) {
$("#mercury_iframe").contents().find("#"+this.page_element_id).html(this.page_element_content);
			   // 	$("#"+this.page_element_id).html(this.page_element_content);

			    });

 




		  }
		})
}
	
}


</script>
 
<style type="text/css">
	    #mw-layout-edit-footer-bar{
                clear: both;
                background-color: #000000;
                color: #FFFFFF;
                font-family: sans-serif;
                font-size: 20px;
                font-weight: bold;
                height: 25px;
                z-index: 10000000;
		position:fixed;
		width:100%;
		bottom:0px;
                padding: 20px 20px 20px 10%;
            }
        </style>
<style>
		#ContentSave
		{
		 position:fixed;
		 bottom:40px;
		 right:20px;
		}
		
		</style>
<? if(url_param('layout_editor')== 'yes'): ?>
<style type="text/css">
 
		 
 
		#mw-layout-edit-site-bottom-bar {
			background-color: #F0F0F0 ;
			border-top: 1px solid #CCCCCC ;
			bottom: 0px ;
			font-family: verdana, arial ;
			font-size: 11px ;
			height: 30px ;
			position: fixed ;
			width: 100% ;
			z-index: 1000 ;
			}
 
		#mw-layout-edit-site-bottom-bar-frame {
			height: 30px ;
			margin: 0px 10px 0px 10px ;
			position: relative ;
			}
 
		#mw-layout-edit-site-bottom-bar-content {
			padding: 3px 0px 0px 0px ;
			}
 
		#mw_layout_edit_menu-root {
			background-color: #E8E8E8 ;
			border: 1px solid #D0D0D0 ;
			color: #000000 ;
			display: block ;
			height: 22px ;
			line-height: 22px ;
			text-align: center ;
			text-decoration: none ;
			width: 105px ;
			}
 
		#mw_layout_edit_menu-root:hover {
			background-color: #666666 ;
			border-color: #000000 ;
			color: #FFFFFF ;
			}
 
		#mw_layout_edit_menu {
			background-color: #E8E8E8 ;
			border: 1px solid #666666 ;
			bottom: 32px ;
			display: none ;
			left: 0px ;
			padding: 5px 5px 1px 5px ;
			position: absolute ;
			width: 200px ;
			}
 
		#mw_layout_edit_menu a {
			background-color: #E8E8E8 ;
			border: 1px solid #FFFFFF ;
			color: #000000 ;
			display: block ;
			margin-bottom: 4px ;
			padding: 5px 0px 5px 5px ;
			text-decoration: none ;
			}
 
		#mw_layout_edit_menu a:hover {
			background-color: #666666 ;
			border-color: #000000 ;
			color: #FFFFFF ;
			}
 
		/* -------------------------------------------------- */
		/* -- IE 6 FIXED POSITION HACK ---------------------- */
		/* -------------------------------------------------- */
 
		 
 
		/* To make up for scroll-bar. */
		#mw-layout-edit-site-bottom-bar {
			_bottom: -1px ;
			_position: absolute ;
			_right: 16px ;
			}
 
		/* To make up for overflow left. */
		#mw-layout-edit-site-bottom-bar-frame {
			_margin-left: 26px ;
			}
 
		/* To fix IE6 display bugs. */
		#mw_layout_edit_menu a {
			_display: inline-block ;
			_width: 99% ;
			}
 
	</style>
<script type="text/javascript">
 
 
 
 $(".edit").dblclick( function () { 
								
				mw_save_all()	
				 window.location="<? print $exit_l_ed; ?>";
								
								
	});
 
 
 
		jQuery(function( $ ){
			var mw_layout_edit_menuRoot = $( ".mw_layout_edit_menu-root" );
			var mw_layout_edit_menu = $( ".mw_layout_edit_menu" );
 
			// Hook up mw_layout_edit_menu root click event.
			mw_layout_edit_menuRoot
				.attr( "href", "javascript:void( 0 )" )
				.click(
					function(){
						// Toggle the mw_layout_edit_menu display.
						mw_layout_edit_menu.show();
 
						// Blur the link to remove focus.
						mw_layout_edit_menuRoot.blur();
 
						// Cancel event (and its bubbling).
						return( false );
					}
				)
			;
 
			// Hook up a click handler on the document so that
			// we can hide the mw_layout_edit_menu if it is not the target of
			// the mouse click.
			$( document ).click(
				function( event ){
					// Check to see if this came from the mw_layout_edit_menu.
					if (
						mw_layout_edit_menu.is( ":visible" )  
						){
 
						// The click came outside the mw_layout_edit_menu, so
						// close the mw_layout_edit_menu.
						mw_layout_edit_menu.hide();
 
					}
				}
			);
 
		});
 
	</script>
<div id="mw-layout-edit-site-bottom-bar" class="fixed-position">
  <div id="mw-layout-edit-site-bottom-bar-frame">
    <div id="mw-layout-edit-site-bottom-bar-content"> <a id="mw_layout_edit_menu-root" class="mw_layout_edit_menu-root" href="##">Toggle mw_layout_edit_menu</a>
      <div id="mw_layout_edit_menu" class="mw_layout_edit_menu">
        
       <? $elements = CI::model('template')->getDesignElements();
	   
	  // p($elements );
	   ?>
        
        <div class="mw_load_elements">
        
        
        
                  <? foreach($elements as $element2): ?>
        
            <div class="row mw_load_element" element="<? print $element_dir. '/'.$element2['module'] ?>" >
             
                <div class="module_draggable mw_no_module_mask"  title="<? print addslashes($element2['description']); ?>">
                  <?  // p($element2) ;?>
                    <? if($element2['icon']): ?>
                    <div class="mw_sidebar_module_icon"> <img src="<? print $element2['icon'] ?>" height="40"  /> </div>
                    <? endif; ?>
                    <? print ($element2['name']); ?>
                    <div class="mw_sidebar_module_insert"></div>
                    <? //print $element2['name'] ?>
                    <textarea id="md5_module_<? print md5($element2['module']) ?>" style="display: none"><? print  $element_dir. '/'.$element2['module'] ?></textarea>
                   
                  <tag_to_remove_add_module_string />
                </div>
            </div>
             
             
               <? endforeach; ?>
        
          <div class="row">
            <editable  rel="page" field="custom_field_editable_<? print PAGE_ID ?>_<? print rand(); ?>"> <a href="##">Here is a mw_layout_edit_menu item</a>   </editable>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<? endif; ?>
<div id="ContentSave">
  <!--<button  onclick="mw_load_history_module()">mw_load_history_module()</button>
 -->

  <? if(url_param('layout_editor') != 'yes'): ?>

  <? else : ?>
      <button  onclick="mw_save_all()">Save all</button>
  <a  href="<? print $enter_l_ed;?>">enter layout</a>
  <a  href="<? print url();?>/editmode:n">exit editmode</a>
  <a  href="<? print $exit_l_ed;?>">exit layout</a>
  <? endif; ?>
  <a  href="<? print   $exit_live_edit;?>">Exit live edit</a>
  <a  href="<? print site_url('admin/action:pages');?>">Return to admin</a> </div>
