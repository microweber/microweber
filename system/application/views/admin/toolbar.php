

 <script type="text/javascript">
  merc_src = "<?php   print( ADMIN_STATIC_FILES_URL);  ?>mercury";
   admin_panel = "<?php   print( ADMIN_URL);  ?>/";
   //alert(admin_panel);
 </script>
       <link href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>mercury/stylesheets/mercury.bundle.css" media="screen" rel="stylesheet" type="text/css"/>
       
         <script src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>mercury/javascripts/mercury_loader.js?src=mercury&pack=bundled" type="text/javascript"></script>
    <!--   <script src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>mercury/javascripts/mercury.js" type="text/javascript"></script>
              <script src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>mercury/javascripts/mercury_dialogs.js" type="text/javascript"></script>
-->
       
  
  <script type="text/javascript">
   
      
    </script>
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



 
 
 
 
 
<div id="kcfinder_div"></div>
    
<script type="text/javascript">
 
$(document).ready(function(){
$(".mw_option_field").live("change", function(){

 
	 //alert('Handler for .change() called.');
	//<? print site_url('api/content/save_option') ?>
	//var refresh_modules11 =  $(this).attr('name');
//	alert(refresh_modules11);
	
	
	
	
	
	 var refresh_modules11 =  this.getAttribute("refresh_modules");
	
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
		
		mw.reload_module($(this).attr('option_group'));
		

		
		
		if(refresh_modules11 != undefined && refresh_modules11 != ''){
			refresh_modules11 = refresh_modules11.toString()
			
//alert(refresh_modules11);
			if(window.mw.reload_module != undefined){
				window.mw.reload_module(refresh_modules11);
			}
			
			if(parent.mw.reload_module != undefined){
				parent.mw.reload_module(refresh_modules11);
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
		$('.edit').addClass('mercury-region');
		$('.edit').attr("data-type",'editable');
		 
		
		
 		
		
		
		
 
	});



function mw_save_all(){
nic_save_all();

}


 nic_save_all = function(callback, only_preview){
$(".mw_non_sortable", '.edit').removeClass('mw_non_sortable');

 var master = {}
  // $(".mw_edited").each(function(j){
								   
								   
								   $("#mercury_iframe").contents().find(".edit").each(function(j){
								   
								   
								   
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
				 $.each(data, function(i, item) {
										$("#"+data[i].page_element_id).html(data[i].page_element_content);
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
            
<style>
		#ContentSave
		{
		 position:fixed;
		 bottom:10px;
		 right:20px;
		}
		
		</style>
        <div id="ContentSave">    <!--<button  onclick="mw_load_history_module()">mw_load_history_module()</button>
 <button  onclick="mw_save_all()">Save all</button>-->
 <a  href="<? print site_url('admin/action:pages');?>">Return to admin</a>
 
</div>

 