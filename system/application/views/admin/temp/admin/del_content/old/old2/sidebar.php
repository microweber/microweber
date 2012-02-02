<script type="text/javascript">
    $(document).ready(function() { 
    $(".tablesorter").tablesorter(); 
   
});         
</script>
<script type="text/javascript">
	
	/*function deleteContentItem($id, $remove_element){
		$(function() {
			$("#deleteContentItem_dialog").dialog({
				bgiframe: true,
				resizable: false,
				height:140,
				modal: true,
				overlay: {
					backgroundColor: '#000',
					opacity: 0.5
				},
				buttons: {
					'Delete!': function() {
						
						 $(this).dialog('close');
						$.get("<?php print site_url('admin/content/content_delete/id:')  ?>"+$id, function(data){
						  //alert("Data Loaded: " + data);
						  
						  if( $remove_element != false){
							  $("#"+$remove_element).remove();
						  }
						  
						  
						 
						});
						
					},
					Cancel: function() {
						$(this).dialog('close');
					}
				}
			});
		});
	}*/
	
	
	
	
	function deleteContentItem($id, $remove_element){
		
		
		var answer = confirm("Are you sure?")
	if (answer){
	$("#"+$remove_element).addClass("light_red_background");
		$.get("<?php print site_url('admin/content/content_delete/id:')  ?>"+$id, function(data){
						  //alert("Data Loaded: " + data);
						  
						  if( $remove_element != false){
							  $("#"+$remove_element).fadeOut(); 
						  }
						  
						  
						 
						});
	}
	else{
		return false;
	}

		
		
						
						
				
	}
	
	
	
	
	
	
	
	
	
	</script>

<div style="display:none;">
  <!--dialogs and ajax boxes are here-->
  <div id="deleteContentItem_dialog" title="Are you sure?" class="flora">
    <p>These items will be permanently deleted and cannot be recovered. Are you sure?</p>
  </div>
  <div id="deleteTaxonomyItem_dialog" title="Are you sure?" class="flora">
    <p>These items will be permanently deleted and cannot be recovered, also all the children items will inherit the parent of the deleted item. Are you sure?</p>
  </div>
</div>
