<script type="text/javascript">
	
	function deleteRssContentItem($id){
		
		
		var answer = confirm("Sure?")
		if (answer){
		
		
		
		$.post("<?php print THIS_PLUGIN_URL ?>delete", { id: $id, time: "2pm" },
		function(data){
		$("#row_id_"+$id).remove();
		});
		
		
		
		
		
		
		
	}
	else{
		return false;
	}

		
		
		
		
		
		
		
	}
	
	
	</script>
<?php if(!empty($form_values)) :   ?>

<table border="0" class="tablesorter" id="sortedtable" cellspacing="0" cellpadding="0">
  <thead>
    <tr>
      <th scope="col">ID</th>
      <th scope="col">Url</th>
      <th scope="col">Name</th>
      <th scope="col">Active?</th>
      <th scope="col">Created</th>
      <th scope="col">Get</th>
      <th scope="col">Edit</th>
      <th scope="col">Delete</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($form_values as $item):  ?>
    <tr id="row_id_<?php print $item['id']; ?>">
      <td><?php print $item['id']; ?> </td>
      <td><a href="<?php print $item['feed_url']; ?>" target="_blank"><?php print $item['feed_url']; ?></a> </td>
      <td><?php print ($item['feed_name']) ?></td>
      <td><?php print ($item['is_active']) ?></td>
      <td><?php print ($item['created_on']) ?></td>
       <td><a href="<?php print THIS_PLUGIN_URL ?>process_feed_by_id_now/id:<?php print $item['id']; ?>">Get now</a> </td>
      
      <td><a href="<?php print THIS_PLUGIN_URL ?>edit/id:<?php print $item['id']; ?>">Edit</a> </td>
      <td><a  href="javascript:deleteRssContentItem(<?php print $item['id']; ?>)">Delete</a> </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php else : ?>
No posts found!
<?php endif; ?>
