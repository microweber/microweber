<script type="text/javascript">

function rss_auto_title(){
$url = $("#feed_url").val();
$.post("<?php print THIS_PLUGIN_URL ?>rss_get_title", { url: $url, time: "2pm" },
  function(data){
  //  alert("Data Loaded: " + data);
  $url = $("#feed_name").val(data);
  });
  
  }
  
  
function rss_set_active_content_category($id){
$("#feed_content_category").val($id);
}

  
  
</script>








<fieldset>
<legend>Edit RSS feed</legend>
<form method="post" action=""  enctype="multipart/form-data">

<table width="700" border="0" cellspacing="5" cellpadding="5">
  <tr>
    <td width="100">URL:</td>
    <td width="450"><input name="id" type="hidden" value="<?php print $form_values['id'] ?>" /><input name="feed_url" id="feed_url" type="text" value="<?php print $form_values['feed_url'] ?>" style="width:100%" /></td>
    <td width="150">  </td>
  </tr>
  <tr>
    <td>Name:</td>
    <td><input name="feed_name" id="feed_name" type="text" value="<?php print $form_values['feed_name'] ?>"   style="width:100%"  /></td>
    <td><a class="ovalbutton" href="javascript:rss_auto_title()"><span><img src="<?php print_the_static_files_url() ; ?>/icons/lightning_small.png" alt=" " border="0">Auto</span></a></td>
  </tr>
  <tr>
    <td>Check Interval minutes:</td>
    <td><input name="feed_check_interval" type="text"  value="<?php print $form_values['feed_check_interval'] ?>" style="width:100px;" /></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Category:</td>
    <td>
    
    
       <?php $data = array();
		$data['taxonomy_type'] = 'category';
		$data['to_table'] = 'table_content';
		
		
		$orderby[0] = 'taxonomy_value';
		$orderby[1] = 'ASC';
		$cats = $this->taxonomy_model->taxonomyGet($data, $orderby ); 
		
		//var_dump($cats);
		foreach($cats as $c){
			
		}
		?>
        

 <script type="text/javascript">
		<!--


/*Name  	Type  	Description
id 	Number 	Unique identity number.
pid 	Number 	Number refering to the parent node. The value for the root node has to be -1.
name 	String 	Text label for the node.
url 	String 	Url for the node.
title 	String 	Title for the node.
target 	String 	Target for the node.
icon 	String 	Image file to use as the icon. Uses default if not specified.
iconOpen 	String 	Image file to use as the open icon. Uses default if not specified.
open 	Boolean 	Is the node open.

Example

mytree.add(1, 0, 'My node', 'node.html', 'node title', 'mainframe', 'img/musicfolder.gif');







Variable  	Type  	Default  	Description
target 	String 	true 	Target for all the nodes.
folderLinks 	Boolean 	true 	Should folders be links.
useSelection 	Boolean 	true 	Nodes can be selected(highlighted).
useCookies 	Boolean 	true 	The tree uses cookies to rember it's state.
useLines 	Boolean 	true 	Tree is drawn with lines.
useIcons 	Boolean 	true 	Tree is drawn with icons.
useStatusText 	Boolean 	false 	Displays node names in the statusbar instead of the url.
closeSameLevel 	Boolean 	false 	Only one node within a parent can be expanded at the same time. openAll() and closeAll() functions do not work when this is enabled.
inOrder 	Boolean 	false 	If parent nodes are always added before children, setting this to true speeds up the tree.
Example

mytree.config.target = "mytarget";



*/
		d = new dTree('d');
		d.config.useCookies = true;

		//d.add(0,-1,'Category', '<?php print site_url('admin/content/posts_manage/'); ?>'); 
		d.add(0,-1,'Category', ''); 
		
		<?php foreach($cats as $item) : ?>
		<?php //$link = site_url('admin/content/posts_manage/categories:'. $item['id']);
		$link = 'javascript:rss_set_active_content_category('. $item['id'] .')';   
		$link  = addslashes($link ); 
		$thumb = $this->taxonomy_model->getThumbnail($item['id'] , 16); 
		if($thumb == ''){
		$thumb = 'false';	
		} else {
			$thumb = "'$thumb'";	
		}
		?> 
	  <?php // $actve_ids = $content_selected_categories; 
	   $actve_ids = array($form_values['feed_content_category']); 
	  ?>
	  <?php $is_open = false;	
	  if(!empty($actve_ids )) :?>		
	  <?php if(in_array($item['id'], $actve_ids ) == true){
	  $is_open = '<img src="'.THE_STATIC_URL.'icons/arrow.png"  border="0" alt=" " />';	
	  $is_open = addslashes($is_open);
	  } 
	  
	  ?>
	  <?php endif; ?>
		
		
		d.add(<?php print $item['id'] ?>,<?php print $item['parent_id'] ?>,'<?php print $is_open ?><?php print addslashes(character_limiter($item['taxonomy_value'], 50, ' ') )?>','<?php print $link ; ?>',false,false,<?php print $thumb ?>,<?php print $thumb ?>);
		
		<?php endforeach; ?>
		document.write(d);

		//-->
		
		<?php $actve_ids = $content_selected_categories; ?>
		<?php if(!empty($actve_ids )) :?>
<?php foreach($actve_ids as $item) : ?>
d.openTo(<?php print $item ; ?>, true);

<?php endforeach; ?>
<?php endif; ?>
d.openAll();
	</script>
    
    
    
    
    
    
    
    
    <input name="feed_content_category" id="feed_content_category" type="text" value="<?php print $form_values['feed_content_category'] ?>"  style="width:100px;" />
    
    
    
    
    
    
    
    </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Active: </td>
    <td><select name="is_active">
      <option  <?php if($form_values['is_active'] == 'n' ): ?> selected="selected" <?php endif; ?>  value="n">no</option>
      <option  <?php if($form_values['is_active'] == 'y' ): ?> selected="selected" <?php endif; ?>  value="y">yes</option>
    </select></td>
    <td>&nbsp;</td>
  </tr>
  
  
  
   <tr>
    <td>no_follow: </td>
    <td><select name="original_link_no_follow">
      <option  <?php if($form_values['original_link_no_follow'] == 'n' ): ?> selected="selected" <?php endif; ?>  value="n">no</option>
      <option  <?php if($form_values['original_link_no_follow'] == 'y' ): ?> selected="selected" <?php endif; ?>  value="y">yes</option>
    </select></td>
    <td>&nbsp;</td>
  </tr>
  
  
  
  
  <tr>
    <td><input name="save" type="submit" value="save" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>



<br />
</form>


</fieldset>





