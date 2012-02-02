<script type="text/javascript">
    $(document).ready(function() { 
    $(".tablesorter").tablesorter(); 
   
});         
</script>


<script type="text/javascript">
	
	function deleteContentItem($id, $remove_element){
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









<div class="box">
<h2>Actions</h2>
  <ul>
    <li <?php if( $className == 'content' and $functionName == 'index')  : ?> class="active" <?php endif; ?> >
    
    
    <a href="<?php print site_url('admin/content/index')  ?>">
    <img src="<?php print_the_static_files_url() ; ?>admin/icons/silk/house.png"  border="0" alt=" " />
    Index</a></li>
  </ul>
</div>


<div class="box">
<h2>Posts</h2>
  <ul>
    <li <?php if( $className == 'content' and $functionName == 'posts_manage')  : ?> class="active" <?php endif; ?> ><a href="<?php print site_url('admin/content/posts_manage')  ?>"><img src="<?php print_the_static_files_url() ; ?>admin/icons/silk/folder.gif"  border="0" alt=" " />Manage posts</a></li>
    <li <?php if( $className == 'content' and $functionName == 'posts_edit')  : ?> class="active" <?php endif; ?> ><a href="<?php print site_url('admin/content/posts_edit/id:0')  ?>">
    
<img src="<?php print_the_static_files_url() ; ?>admin/icons/silk/report.png"  border="0" alt=" " />    
    
    
    Add/Edit posts</a></li>
  </ul>
</div>




<div class="box">
<h2>Pages</h2>
  <ul>
    <li <?php if( $className == 'content' and $functionName == 'pages_index')  : ?> class="active" <?php endif; ?> ><a href="<?php print site_url('admin/content/pages_index')  ?>"><img src="<?php print_the_static_files_url() ; ?>admin/icons/silk/outline.png"  border="0" alt=" " />List pages</a></li>
    <li <?php if( $className == 'content' and $functionName == 'pages_edit')  : ?> class="active" <?php endif; ?> >
    <a href="<?php print site_url('admin/content/pages_edit/id:0')  ?>">
    <img src="<?php print_the_static_files_url() ; ?>admin/icons/silk/table_edit.png"  border="0" alt=" " />	Add/Edit pages
        
        
        
        
     </a></li>
  </ul>
</div>

<div class="box">
<h2>Menus</h2>
  <ul>
    <li <?php if( $className == 'content' and $functionName == 'menus')  : ?> class="active" <?php endif; ?> ><a href="<?php print site_url('admin/content/menus')  ?>"><img src="<?php print_the_static_files_url() ; ?>admin/icons/silk/tab_edit.png"  border="0" alt=" " />Edit menus</a></li>
    <!--<li <?php if( $className == 'content' and $functionName == 'pages_edit')  : ?> class="active" <?php endif; ?> ><a href="<?php print site_url('admin/content/pages_edit/id:0')  ?>">Add/Edit pages</a></li>-->
  </ul>
</div>

<div class="box">
<h2>Taxonomy</h2>
  <ul>
    <li <?php if( $className == 'content' and $functionName == 'taxonomy_categories')  : ?> class="active" <?php endif; ?> ><a href="<?php print site_url('admin/content/taxonomy_categories')  ?>"><img src="<?php print_the_static_files_url() ; ?>admin/icons/silk/text_list_bullets.png"  border="0" alt=" " />Edit categories</a></li> 
    <!--<li <?php if( $className == 'content' and $functionName == 'pages_edit')  : ?> class="active" <?php endif; ?> ><a href="<?php print site_url('admin/content/pages_edit/id:0')  ?>">Add/Edit pages</a></li>-->
  </ul>
</div>