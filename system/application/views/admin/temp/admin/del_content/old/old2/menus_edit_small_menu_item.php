<?php $menu_item = $form_values; ?>
<script type="text/javascript">
$(document).ready(function () {
  $('#tabs_<?php print $form_id ?>').tabs({ fxAutoHeight: true });
});


</script>
<script>
  $(document).ready(function(){
    $("#categories_tree_<?php print $form_id ?>").treeview({
				collapsed: false,
				animated: "fast",
				//control:"#sidetreecontrol",
				//persist: "location"
			});
			
			
			$("#pages_tree_<?php print $form_id ?>").treeview({
				collapsed: false,
				animated: "fast",
				//control:"#sidetreecontrol",
				//persist: "location"
			});
			
			
			
			
			
			
			
  });
  </script>
<br />



<table width="380" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
    
    
    
    
     <div id="tabs_<?php print $form_id ?>" class="flora" style="overflow:scroll; display:block; height:450px; width:380px;">
    <ul class="tabs">
  	<li class="ui-state-active"><a href="#tab1">Page</a></li>
     <li><a href="#tab2">Category</a></li>
      <li><a href="#tab3">Custom</a></li>
  	</ul>
  
  <div id="tab1">
  
  
   <div id="pages_tree_<?php print $form_id ?>"  >
  <?php $pages = $this->content_model->contentGetPages();  ?>
  <ul>Pages
  <?php foreach($pages as $page ) :?>
  <li><?php print $page['content_title'] ?>  <a href='javascript:select_category_for_menu_item("<?php print $page['id'] ?>", "<?php print $form_id ?>", "page")'>Select</a></li>
  
  
  
  
  <?php endforeach; ?>
  </ul>
  
  
   </div>
  
  
  
  
  </div><!--/tab1-->
  <div id="tab2"> 
  
  <div id="categories_tree_<?php print $form_id ?>" style="overflow:scroll; height:430px;"  >
  <?php $link = "";
 $this->firecms = get_instance();
 //$this->firecms->content_model->content_helpers_getCaregoriesUlTree(0, "<a href='$link{id}'>Edit: {taxonomy_value}</a> | <a href='javascript:taxonomy_categories_delete({id})'    >Delete</a> | <a href='javascript:taxonomy_categories_select({id})'    >Select</a>"); 
 
 
 $this->firecms->content_model->content_helpers_getCaregoriesUlTree(0, "<div>&nbsp;&nbsp;{taxonomy_value} <a href='javascript:select_category_for_menu_item(\"{id}\", \"$form_id\", \"category\")'>Select</a></div>");
 
 
 
 
 ?>
</div>
  
  
  
  
  </div>
  <div id="tab3">
  3
  </div>
   </div>   
  <div class="clear"></div><br />
    
    
    
    
    
    
    
    
    
    </td>
  </tr>
 </table>



