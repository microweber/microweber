<script type="text/javascript">
function check_all_categories(){
			alert('test');
			/*$("#paradigm_all").click(function()				
			{
				var checked_status = this.checked;
				$("input[@name=paradigm]").each(function()
				{
					this.checked = checked_status;
				});
			});			*/		
}
function uncheck_all_categories(){
			alert('test');
			/*$("#paradigm_all").click(function()				
			{
				var checked_status = this.checked;
				$("input[@name=paradigm]").each(function()
				{
					this.checked = checked_status;
				});
			});			*/		
}
	
	
$(document).ready(function() {
 do_the_content_search_assign()   
test = $("#subheader");
	var tax = $("input[name='taxonomy_categories[]']");
	tax.click(function(){
		do_the_content_search_assign();
		})
	
	
	var tags = $("input[name='taxonomy_tags[]']");
	tags.click(function(){
		do_the_content_search_assign();
		})
	
	
	
})
       
	   
function do_the_content_search_assign(){
		var tax_parent = $("#categories_container");
		var find_c = tax_parent.find(":checked");
			var cc = find_c.serializeArray();
			//alert(cc)
			// $("#the_content_search_form").empty();
			var temp = new Array();
			 jQuery.each(cc, function(i, field){
       		 //$("#the_content_search_form").append(field.value + " ");
			 temp[i] = field.value;
      		});
			temp = temp.join(',') 
			$("#the_content_search_form_categories").val(temp);
			//
			
			
			
			var tax_parent = $("#tags_container");
		var find_c = tax_parent.find(":checked");
			var cc = find_c.serializeArray();
			//alert(cc)
			// $("#the_content_search_form").empty();
			var temp = new Array();
			 jQuery.each(cc, function(i, field){
       		 //$("#the_content_search_form").append(field.value + " ");
			 temp[i] = field.value;
      		});
			temp = temp.join(',') 
			$("#the_content_search_form_tags").val(temp);
			//
	
			
	
}	   
	   
	   

function do_the_content_search(){
	$("#the_content_search_form").submit();
	
}


	
</script>




<form method="post" action="<?php print site_url('admin/content/posts_manage_do_search');  ?>" id="the_content_search_form">
	<input type="hidden" value="" name="categories" id="the_content_search_form_categories" />
    <input type="hidden" value="" name="tags" id="the_content_search_form_tags"  />

</form>


<table border="0" cellpadding="0" cellspacing="0" id="istable">
  <tr valign="top">
    <td>
    <fieldset class='categories_container_fieldset' >
    <legend>Categories</legend>
    <a href="javascript:check_all_categories()" >Check all</a> | <a href="javascript:uncheck_all_categories()" >Select none</a>
    <br />
        <div class="ullist" id="categories_container">
    <?php // var_dump($content_selected_categories) ;  ?>
    <?php $actve_ids = array();
	$actve_ids = $content_selected_categories;
	$active_code = " checked='checked'  ";
	
	 $this->firecms = get_instance();
 $this->firecms->content_model->content_helpers_getCaregoriesUlTree(0, "<label><input name='taxonomy_categories[]' type='checkbox'  {active_code}   id='category_selector_{id}' value='{id}' />{taxonomy_value}</label>", $actve_ids , $active_code , $remove_ids = false, $removed_ids_code = false);  ?>
 
 </div>
    <input name="filter" type="button"  value="Filter" onclick="do_the_content_search();" />
    </fieldset>
    
    
    
     <fieldset class='tags_container_fieldset' >
    <legend>Tags</legend>
<?php if(empty($content_selected_tags)){
$content_selected_tags = array();	
} 
//var_dump($content_selected_tags);
?>

     <div class="ullist" id="tags_container">
    <?php foreach($avalable_tags as $tag): ?>
    <label><input name="taxonomy_tags[]" type="checkbox"    <?php if(in_array($tag, $content_selected_tags) == true) : ?>   checked="checked" <?php endif; ?>     value="<?php print $tag; ?> " /><?php print $tag; ?></label><br />

    <?php endforeach; ?>
    </div>
    
     
    
     <input name="filter1" type="button"  value="Filter" onclick="do_the_content_search();" />
    </fieldset>
    
    
    
    
    
    
    </td>
    <td>
    
    
    <?php //var_dump($latest_posts);  ?>
       <hr />
    <h2>Last edited <?php print count($latest_posts) ?> posts</h2>
    
       <?php if(!empty($latest_posts)) :   ?>
<table border="0" class="tablesorter tables" id="sortedtable" cellspacing="0" cellpadding="0">
<thead>
  <tr>
    <th scope="col">ID</th>
    <th scope="col">Go</th>
    <th scope="col">Url</th>
    <th scope="col">Title</th>
    <th scope="col">Active?</th>
    <th scope="col">Created</th>
    <th scope="col">Updated</th>
    <th scope="col">Edit</th>
    <th scope="col">Delete</th>
  </tr>
  </thead>
  <tbody> 
  <?php foreach($latest_posts as $item):  ?>
  <tr id="content_row_id_<?php print $item['id']; ?>">
    <td><?php print $item['id']; ?><a href="<?php print $this->content_model->contentGetHrefForPostId($item['id']) ; ?>" target="_blank" ><img src="<?php print_the_static_files_url() ; ?>admin/icons/silk/page_go.png"  border="0" alt=" " /></a>
    </td>
      <td>
    
      <div style="display:block; overflow:hidden; height:32px; width:32px; vertical-align:middle; text-align:center">
        <img src="<?php print   $thumb = $this->content_model->contentGetThumbnailForContentId($item['id'], 32); ?>" height="32" /></div>
      
      </td>
    <td>
	<?php //print $the_url.'/'.$item['content_url'] ?> 
    <?php print $this->content_model->contentGetHrefForPostId($item['id']) ; ?>
    </td>
    <td><?php print ($item['content_title']) ?></td>
    <td><?php print ($item['is_active']) ?></td>
    <td><?php print ($item['created_on']) ?></td>
    <td><?php print ($item['updated_on']) ?></td>
   <td> <a  target="_blank"  href="<?php print site_url('admin/content/posts_edit/id:'.$item['id'])  ?>">Edit post</a> </td>
   
 <td>  <a href="javascript:deleteContentItem(<?php print $item['id']; ?>, 'content_row_id_<?php print $item['id']; ?>')">Delete</a>
    </td>
  </tr>
  <?php endforeach; ?>
  </tbody>
</table>
   <?php endif; ?>    
    
    
    
    
    <br />
<br />

    
    <hr />
    <br />
<br />

    <h2>Posts</h2>
    
    
    
    
    <?php $this->firecms = get_instance();	 ?>
   
    <?php if(!empty($form_values)) :   ?>
<table border="0" class="tablesorter tables" id="sortedtable" cellspacing="0" cellpadding="0">
<thead>
  <tr>
    <th scope="col">ID</th>
    <th scope="col">Go</th>
    <th scope="col">Url</th>
    <th scope="col">Title</th>
    <th scope="col">Active?</th>
    <th scope="col">Created</th>
    <th scope="col">Updated</th>
    <th scope="col">Edit</th>
    <th scope="col">Delete</th>
  </tr>
  </thead>
  <tbody> 
  <?php foreach($form_values as $item):  ?>
  <tr id="content_row_id_<?php print $item['id']; ?>">
    <td><?php print $item['id']; ?><a href="<?php print $this->content_model->contentGetHrefForPostId($item['id']) ; ?>" target="_blank" ><img src="<?php print_the_static_files_url() ; ?>admin/icons/silk/page_go.png"  border="0" alt=" " /></a>
    </td>
      <td>
    
      <div style="display:block; overflow:hidden; height:64px; width:64px; vertical-align:middle; text-align:center">
        <img src="<?php print   $thumb = $this->content_model->contentGetThumbnailForContentId($item['id'], 64); ?>" height="64" /></div>
      
      </td>
    <td>
	<?php //print $the_url.'/'.$item['content_url'] ?> 
    <?php print $this->content_model->contentGetHrefForPostId($item['id']) ; ?>
    </td>
    <td><?php print ($item['content_title']) ?></td>
    <td><?php print ($item['is_active']) ?></td>
    <td><?php print ($item['created_on']) ?></td>
    <td><?php print ($item['updated_on']) ?></td>
   <td> <a target="_blank" href="<?php print site_url('admin/content/posts_edit/id:'.$item['id'])  ?>">Edit post</a> </td>
   
 <td>  <a  href="javascript:deleteContentItem(<?php print $item['id']; ?>, 'content_row_id_<?php print $item['id']; ?>')">Delete</a>
    </td>
  </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php if($content_pages_count > 1) :   ?>
    <div align="center" id="admin_content_paging">
    <?php for ($i = 1; $i <= $content_pages_count; $i++) : ?>
<a href="<?php print  $content_pages_links[$i]  ?>" <?php if($content_pages_curent_page == $i) :   ?> class="active" <?php endif; ?> ><?php print $i ?></a>  
<?php endfor; ?>
    </div>
  <?php endif; ?>  
   <?php endif; ?>     
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    </td>
  </tr>

</table>





