<? if($params['id'] == false){
	
	$params['id'] =url_param('id');
}

if($params['name'] != false){
	
	
	$id =  CI::model('content')->getMenuIdByName($params['name']) ;
	 $params['id'] = $id;
}

?>
  <?
	  
	  if($params['module_id']){
		  if(intval($params['id']) == 0){
		$params['id'] = CI::model('content')->getMenuByMenuUnuqueId($params['module_id']);
		  }
	  }
	  
	  
	  //p($params);
	  ?>
<? 
$rand = rand();

if($params['save']){

	
	?>

save
<?

	return;
}




?>

<? if(intval($params['id']) == 0):  ?> 

no such menu

<? endif; ?>


<?php  $menu_data = CI::model('content')->getMenus(array('id' => $params['id']));
		if(!empty($menu_data)){
		$menu_data = $menu_data[0];	
		}
		//p($menu_data);
		?>
<? 
 
 //p($params);

$pages = get_pages_old() ;
//p($pages);
?>
<div class="menu_module_admin" id="menu_module_admin_<? print $params['module_id'];  ?>">
<script type="text/javascript">
var add_menus_controlls = function(){
	$('#menu_module_admin_<? print $params['module_id'];  ?> .remove_me').remove();
	//$('.menu_element').append('<div class="remove_me"><input name="edit" onclick="edit_menu_item($(this).parent(\'li.menu_element\').attr(\'id\'))" type="button" value="edit" /></div>');
	
	// Loop over each hottie.
$( "#menu_module_admin_<? print $params['module_id'];  ?> li.menu_element" ).each(
 
	// For each hottie, run this code. The "indIndex" is the
	// loop iteration index on the current element.
	function( intIndex ){
 
		// Bind the onclick event to simply alert the
		// iteration index value.
		$( this ).bind (
			"click",
			function(event){
				somid = $( this ).attr('id');
				edit_menu_item(somid);
				event.preventDefault();
				//alert( "Hottie index: " + intIndex );
			}
			);
 
	}
 
	);
	
	
	
}
 
var add_to_menu = function($id, $title){
	$.post("<? print site_url('api/content/save_menu_items') ?>", { id: "0", content_id: $id, menu_id: "<? print $params['id'] ?>", item_title: $title },
	function(data){
	$('#menu_module_admin_<? print $params['module_id'];  ?> ul.menu').append('<li class="menu_element" onclick="edit_menu_item('+data+');" id=menu_item_"'+data+' ><div>'+$title+'</div></li>');
	add_menus_controlls();
	});
}


var save_edited_item = function($form_id){
	data123 = $('#'+$form_id).serialize();
	$.post("<? print site_url('api/content/save_menu_items') ?>",  data123 ,	function(data1){ 
																						// alert(data1);
																						 });
}

var delete_edited_item = function($form_id){
	if (confirm("Are you sure you want to delete")) {
   	data123 = $('#'+$form_id).serialize();
	
	
	$.ajax({
   type: "POST",
   url: "<? print site_url('api/content/delete_menu_item') ?>",
   data: data123,
     dataType: "json",
   success: function(msg){
	   $('#menu_item_'+msg.id).fadeOut();
	     $('#'+$form_id).fadeOut();
	   
	   
    //menu_item_771 alert( "Data Saved: " + msg );
   }
 });
	
	
 
	
	
  }
	

}





var edit_menu_item = function($id){
	
	
	//alert($id);
	
	 
	 data1 = {}
   data1.module = 'admin/content/menu_item_edit';
    data1.id = $id;
 
   $.ajax({
  url: '<? print site_url('api/module') ?>',
   type: "POST",
      data: data1,

      async:true,

  success: function(resp) {

   //$('#edit_menu_item').html(resp);

   mw.modal.init({
     title:"Edit Menu",
     html:resp,

     height:'auto',
     width:500,
     customPosition:{
       top:$(window).scrollTop()+50,
       left:'center'
     }
   })

  }
    });
	
	
 
	
	
	
	
}
	 
	
	</script>
    
 
    
<table width="100%" border="0">
  <tr>
    <td><h2 style="padding: 10px;"><? print $menu_data['menu_title'] ;?></h2></td>
  </tr>
  <tr>
    <td><h3 style="padding: 10px;">Pages</h3>
      <ul class="menus_list">
        <? foreach($pages['posts'] as $page): ?>
        <li>
          <div>Page <? print $page['content_title'] ?> <a class="btn" onclick="add_to_menu('<? print $page['id'] ?>', '<? print addslashes($page['content_title']) ?>')">add</a></div>
        </li>
        <? endforeach; ?>
      </ul>
      <h3 style="padding: 20px 10px 0;">Menu items</h3>
      
      
    
      
      <div class="" id="edit_memu_items<? print $params['module_id'];  ?>">
        <? $menu_items = CI::model('content')->menuTree($params['id']) ;

print $menu_items ; ?>
      </div></td>
    <td><div id="edit_menu_item"></div></td>
  </tr>
</table>
<script> 
  
	$(document).ready(function(){
		add_menus_controlls();
		
		
		
		$('#edit_memu_items<? print $params['module_id'];  ?> ul.menu').sortable({
					opacity: '0.5',
					containment: 'parent',
					items: 'ul, li' ,
					//forcePlaceholderSize: true,
					//forceHelperSize: true ,
					 
					update: function(e, ui){
						serial = $(this).sortable("serialize");
						$.ajax({
							url: "<?php print site_url('api/content/save_menu_items_order')  ?>",
							type: "POST",
							data: serial,
							// complete: function(){},
							success: function(feedback){
								add_menus_controlls();
								 mw.reload_module('content/menu');
								 							 
								 
							//alert(feedback);
								//$('#data').html(feedback);
							}
							// error: function(){}
						});
					}
				});
							   
 
/*		$('#menu_module_admin_<? print $params['module_id'];  ?> ul.menu').nestedSortable({
			disableNesting: 'no-nest',
			forcePlaceholderSize: true,
			connectWith: "ul.menu", 
			accept: 'menu_element',
			//handle: 'a',
			items: 'li',
			opacity: .6,
			placeholder: 'placehulder',
			tabSize: 25,
			//tulerance: 'pointer',
			update: function(serialized) {    
			serialized = $('ul.menu').nestedSortable('serialize');
			
			
                     // alert(arraied);   
					 
					 $.post("<? print site_url('api/content/save_menu_items') ?>", { items: serialized, menu_id: "<? print $params['id'] ?>",reorder: true },
   function(data){
    // alert("Data Loaded: " + data);
	add_menus_controlls();
   });
					 
					 
               } 
		}); */
 
		 
 
	});
 
	function dump(arr,level) {
		var dumped_text = "";
		if(!level) level = 0; 
 
		//The padding given at the beginning of the line.
		var level_padding = "";
		for(var j=0;j<level+1;j++) level_padding += "    ";
 
		if(typeof(arr) == 'object') { //Array/Hashes/Objects
			for(var item in arr) {
				var value = arr[item];
 
				if(typeof(value) == 'object') { //If it is an array,
					dumped_text += level_padding + "'" + item + "' ...\n";
					dumped_text += dump(value,level+1);
				} else {
					dumped_text += level_padding + "'" + item + "' => \"" + value + "\"\n";
				}
			}
		} else { //Stings/Chars/Numbers etc.
			dumped_text = "===>"+arr+"<===("+typeof(arr)+")";
		}
		return dumped_text;
	}
    
    </script>
</div>