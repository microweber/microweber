<script type="text/javascript">
	
	function deleteTag($taxonomy_value, $row){
		
		
		var answer = confirm("Sure?")
		if (answer){
		
		
		$("#"+$row).addClass("light_red_background");
	
		$.post("<?php print site_url('admin/content/taxonomy_tags_delete'); ?>", { taxonomy_value: $taxonomy_value, time: "2pm" },
		function(data){
		$("#"+$row).fadeOut();
		});

	}
	else{
		return false;
	}
		
	}
	
	
	</script>


<?php if(!empty($form_values)) :   ?>
<form method="post" action="<?php print site_url('admin/content/taxonomy_tags_delete_less_than'); ?>">
<table border="0" cellspacing="0" cellpadding="0" align="right">
  <tr>
    <td>&nbsp;</td>
    <td align="right">Delete tags less popular than: <input name="less_than" type="text" value="0" />&nbsp; <input name="delete" type="submit" value="delete" /></td>
  </tr>
</table>
</form>
<br />
<br />


      <table border="0" class="tablesorter tables" id="sortedtable" cellspacing="0" cellpadding="0" width="500">
        <thead>
          <tr>
          

            <th scope="col">Tag</th>
             <th scope="col">Popularity</th>
          
           
            <th scope="col">Delete</th>
          </tr>
        </thead>
        <tbody>
          <?php $i = 1;
		  foreach($form_values as $item):  ?>
          <tr id="tags_row_id_<?php print $i; ?>">
            <td>
			
			<form method="post" action="<?php print site_url('admin/content/taxonomy_tags_update'); ?>">
			<input name="taxonomy_value" type="text" value="<?php print ($item['taxonomy_value']) ?>">
       <input name="tag_old_name" type="hidden" value="<?php print ($item['taxonomy_value']) ?>">
       <input name="save" type="submit" value="save">
            </form>
            </td>
            <td><?php print ($item['qty']) ?></td>
           
            <td><a  href="javascript:deleteTag('<?php print ($item['taxonomy_value']) ?>', 'tags_row_id_<?php print $i; ?>')">Delete</a></td>
          </tr>
          <?php $i ++; endforeach; ?>
        </tbody>
      </table>
    
      <?php else : ?>
      No tags found!
      <?php endif; ?>