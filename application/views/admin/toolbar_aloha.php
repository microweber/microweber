

<script type="text/javascript">
GENTICS_Aloha_base = "<?php   print( ADMIN_STATIC_FILES_URL);  ?>aloha/";
</script>

 <link rel="stylesheet" href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>aloha/css/aloha.css" type="text/css">
 <link rel="stylesheet" href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>aloha/plugins/common/image/css/image.css" type="text/css">
  <link rel="stylesheet" href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>aloha/plugins/common/format/css/format.css" type="text/css">
<!-- <script src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>aloha/lib/aloha.js" data-aloha-plugins="common/format,common/image,common/highlighteditables,common/list,common/link,common/undo,common/paste,common/table" type="text/javascript"></script> -->
 <script type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>aloha/lib/aloha.js" data-aloha-plugins="common/format,common/table,common/list,common/link,common/image,common/highlighteditables,common/block,common/undo,common/paste,common/abbr,common/commands"  ></script>
 
<!--

<script type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>aloha/examples/aloha/plugins/com.gentics.aloha.plugins.Format/plugin.js"></script>
<script type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>aloha/examples/aloha/plugins/com.gentics.aloha.plugins.Table/plugin.js"></script>
<script type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>aloha/examples/aloha/plugins/com.gentics.aloha.plugins.List/plugin.js"></script>
<script type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>aloha/examples/aloha/plugins/com.gentics.aloha.plugins.Link/plugin.js"></script>

<script type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>aloha/examples/aloha/plugins/com.gentics.aloha.plugins.HighlightEditables/plugin.js"></script>
<script type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>aloha/examples/aloha/plugins/com.gentics.aloha.plugins.TOC/plugin.js"></script>
<script type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>aloha/examples/aloha/plugins/com.gentics.aloha.plugins.Paste/plugin.js"></script>
<script type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>aloha/examples/aloha/plugins/com.gentics.aloha.plugins.Paste/wordpastehandler.js"></script>-->
 
 
<!-- turn an element into editable Aloha continuous text -->
 

<script type="text/javascript">
 

	// http://requirejs.org/docs/errors.html#mismatch
	// ../../plugins/common/link/extra/linklist.js

	// Bind to Aloha Ready Event
	Aloha.ready( function() {
		var $ = Aloha.jQuery;
		$('.edit').aloha();
		 
			
	});
	</script>
    
    
<script type="text/javascript">
 
$(document).ready(function() {
	//	$('.edit').aloha();
		
		
 
	});



function mw_save_all(){
nic_save_all();
}


 nic_save_all = function(callback, only_preview){
$(".mw_non_sortable", '.edit').removeClass('mw_non_sortable');

 var master = {}
  // $(".mw_edited").each(function(j){
								   $(".edit").each(function(j){


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
          async:$async_save,
		  beforeSend :  function() {
			
			  window.saving =true;
			 // $( "#ContentSave" ).fadeOut();
		 
		  },
		  success: function(data) {
			  
			  
			  			   
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


 


</script>

<style>
		#ContentSave
		{
		 position:fixed;
		 bottom:10px;
		 right:20px;
		}
		
		</style>
        <div id="ContentSave">
 <button  onclick="mw_save_all()">Save all</button>
 <a  href="<? print site_url('admin/action:pages');?>">Return to admin</a>
</div>

 