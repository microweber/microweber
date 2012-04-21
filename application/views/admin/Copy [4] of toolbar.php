<style>
body {
 margin-top:30px;	
}

  .sortable-placeholder, .sortable-dragging {
		  border: 1px dashed #CCC;
		  /* padding-top:15px;
		    padding-bottom:15px;
		 margin-top:10px;
		 margin-bottom:10px;*/
		  background-color:#FFC !important;
		   
	  }
  
  .ui-resizable-helper { border: 2px dotted #00F; }
  [contenteditable=true], .outline, .mw_dragover {
    outline: 1px dotted #cccccc;
	
}
[contenteditable=true]:hover {
    outline: 1px dotted #ff0000;
}


.edit .module, .edit img {
 resize: both;  
}


.ui-selecting {
    background: #aad284;
    border:1px solid #666666;
}
.ui-selected, .ui-multidraggable {
    background: #e2ecf5;
    border:1px solid #666666;
}
 
.ui-resizable-helper { border: 2px dotted #00F; } 
.ui-draggable-dragging{
    background-color:#91B168;
               
}

  </style>
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
<style type="text/css">
 
		 
 
		#mw-layout-edit-site-top-bar {
			background-color: #F0F0F0 ;
			border-top: 1px solid #CCCCCC ;
			top: 0px ;
			font-family: verdana, arial ;
			font-size: 11px ;
			height: 30px ;
			position: fixed ;
			width: 100% ;
			z-index: 1000 ;
			}
			
				#mw-layout-edit-site-top-bar-l {
		
			width: 80% ;
			float:left;
			}
			
				#mw-layout-edit-site-top-bar-r {
		
			width: 19% ;
			float:right;
			}
  
		/* To make up for scroll-bar. */
		#mw-layout-edit-site-top-bar {
			_top: -1px ;
			_position: absolute ;
			_right: 16px ;
			}
 
	 
 
	</style>
	<? 
 
 $exit_l_ed = url_param_unset('layout_editor',url());
 $enter_l_ed = $this->core_model->urlConstruct(url(), array('layout_editor' => 'yes'));
  $exit_live_edit = $this->core_model->urlConstruct(url(), array('?editmode' => 'n'));

// var_dump($exit_l_ed , $enter_l_ed);
 ?>
<script type="text/javascript">
  merc_src = "<?php   print( ADMIN_STATIC_FILES_URL);  ?>mercury";
   admin_panel = "<?php   print( ADMIN_URL);  ?>/";
   //alert(admin_panel);
   
   
 </script> 
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>

 <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css"    href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/base/jquery-ui.css"/>
 <script src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>freshereditor/shortcut.js" type="text/javascript"></script>
<script src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>freshereditor/farbtastic/farbtastic.js" type="text/javascript"></script>
<script src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>freshereditor/freshereditor.js" type="text/javascript"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('.edit').freshereditor({toolbar_selector: "#mw-layout-edit-site-top-bar-l"});
	 $(".edit").freshereditor("edit", true);
	 
	   $(".edit").on('change', function() {
             mw_save_all()
            });
	 
	  
	});
	

	</script>
<link href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>freshereditor/freshereditor.css" rel="stylesheet" type="text/css" />
<link href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>freshereditor/farbtastic/farbtastic.css" rel="stylesheet" type="text/css" />
 
<!--<script src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>freshereditor/html5_sortable.js" type="text/javascript"></script>
-->
<!--<script type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>multidraggable/js/ui.multidraggable.js"></script>
 -->
    
    
    
<script src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>boxy/src/javascripts/jquery.boxy.js" type="text/javascript"></script>
<link href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>boxy/src/stylesheets/boxy.css" rel="stylesheet" type="text/css" />
 

 
<script type="text/javascript">

 Boxy.DEFAULTS.title = 'Title';
 Boxy.DEFAULTS.fixed = true;
  Boxy.DEFAULTS.draggable = true;
  Boxy.DEFAULTS.modal = false;
 Boxy.DEFAULTS.unloadOnHide = true;
 
	function open_module_browser(){
					data1 = {}
   data1.module = 'admin/modules/list';
   
   data1.page_id = '<? print intval(PAGE_ID) ?>';
   data1.post_id = '<? print intval(POST_ID) ?>';
   data1.category_id = '<? print intval(CATEGORY_ID) ?>';
   
   
   
   
	Boxy.load2("<? print site_url('api/module') ?>", data1);
 
}

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
 
	  
	  init_sortables()

	  
	  
    });
	
 $(".edit").dblclick( function () { 
			if(window.mw_editables_created == false){					
			 mw_make_editables()
				 }				
								
	});
 
 
 
 
window.mw_editables_created = false;
function mw_make_editables(){
				 
				
	
 
	
				 if(window.mw_drag_started == false && window.mw_handle_hover != true ){
				window.mw_sortables_created = false;
			 if(window.mw_editables_created == false){
				$(".edit [draggable='true']").unbind();
				$(".edit [draggable='true']").removeAttr('draggable');
				$(".edit").find('*').draggable('destroy');
				$(".edit").find('*').resizable('destroy');
				
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
			
			
			$(".edit").find('div,.module,img,ul,ol,a,table,pre,p,span,h1,h2,h3,h4,h5,h6,h7,h8,blockquote').draggable({
			
			// appendTo: 'body',
			 stack: "div",
			 //snapMode: 'outer',
			  //containment: 'document',
			start: function(event, ui) {
            ui.helper.bind("click.prevent",
                function(event) { event.preventDefault(); });
        },
        stop: function(event, ui) {
            setTimeout(function(){ui.helper.unbind("click.prevent");}, 300);
        },
    drag: function(ui, event) {
		
	/*	 i = $(this);
 o = $(this).parent();;
		
        if (i.position().top > o.height() - i.height()) {
            o.height(o.height() + 10);
        }
        if (i.position().left > o.width() - i.width()) {
           // o.width(o.width() + 10);
        }*/
    }
});
			
			$(".edit").find('.module *').disableSelection(); 
			$(".edit").find('.module *').draggable('destroy');
			$(".edit").find('div,.module,img,ul,ol,table,pre').resizable({			helper: "ui-resizable-helper"		});
			
			
		//	$(".edit").find('*:not(.module *),img:not(.module *)').resizable({			helper: "ui-resizable-helper"		});
			
			
		
			
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
  </script>

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
	
	
	  
	
}


 

function mercuryLoaded(){
	 window.Mercury = top.Mercury;
    //  Mercury.trigger('initialize:frame');
	 // alert(1);
	 $('#mercury_iframe').load(function(){
		//	init_sortables()							
    });

 }
 
function mw_save_all(){
nic_save_all();

}


 nic_save_all = function(callback, only_preview){
$(".mw_non_sortable", '.edit').removeClass('mw_non_sortable');





 var master = {}
  // $(".mw_edited").each(function(j){
								   
								   
								   
								   
								   
  
$('.edit').each(function(j){
 
								   
								 
								   
								   
								   
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

<div id="mw-layout-edit-site-top-bar" class="fixed-position"><div id="mw-layout-edit-site-top-bar-l"></div><div id="mw-layout-edit-site-top-bar-r">right</div></div>
<div id="mw-temp"> </div>
<div id="ContentSave">
  <!--<button  onclick="mw_load_history_module()">mw_load_history_module()</button>
 -->
  <? if(url_param('layout_editor') != 'yes'): ?>
  <? else : ?>
  <button  onclick="mw_save_all()">Save all</button>
  <a  href="<? print $enter_l_ed;?>">enter layout</a> <a  href="<? print url();?>/editmode:n">exit editmode</a> <a  href="<? print $exit_l_ed;?>">exit layout</a>
  <? endif; ?>
  <a  href="<? print   $exit_live_edit;?>">Exit live edit</a> <a  href="<? print site_url('admin/action:pages');?>">Return to admin</a> </div>
