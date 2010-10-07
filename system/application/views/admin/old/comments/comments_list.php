


<?php if(!empty($comments)) :   ?>

<?php $funcname_approve = 'approve_comment_'.md5(serialize($comments));
$funcname_delete = 'delete_comment_'.md5(serialize($comments));
?>

<script type="text/javascript">
function <?php print $funcname_approve ?>($id){
	
	$.post("<?php print site_url('admin/comments/approve')  ?>", { id: $id, time: "2pm" },
  function(data){
   // alert("Data Loaded: " + data);
    $("#comment_row_id_"+$id).remove();
  });

	
	
}


function <?php print $funcname_delete ?>($id){
	var answer = confirm("Are you sure?")
	if (answer){
	$.post("<?php print site_url('admin/comments/delete')  ?>", { id: $id, time: "2pm" },
  function(data){
   $("#comment_row_id_"+$id).remove();
  });
	
	
	}
	else{
	//	alert("Thanks for sticking around!")
	}

	
	
	

	
	
}
</script>





<table border="0" class="tablesorter ctab_le" id="sortedtable" cellspacing="0" cellpadding="0"  width="70%" >
  <thead id="sortable">
    <tr>
      <th scope="col">ID</th>
      <th scope="col">To</th>
      <th scope="col">User</th>
      <th scope="col">Comment</th>
      <th scope="col">Created</th>
      <th scope="col">Edit</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($comments as $item):  ?>
    <tr id="comment_row_id_<?php print $item['id']; ?>">
      <td><?php print $item['id']; ?></td>
      <td><?php if($item['to_table'] == 'table_content') : ?>
        <div> <img src="<?php print   $thumb = $this->content_model->contentGetThumbnailForContentId($item['to_table_id'], 64); ?>" height="64" align="left" />
          <ul>
            <li><a href="<?php print $this->content_model->contentGetHrefForPostId($item['to_table_id']) ; ?>"  target="_blank" ><?php print $this->content_model->contentGetHrefForPostId($item['to_table_id']) ; ?></a></li>
            <li>
              <?php $content = $this->content_model->contentGetById($item['to_table_id']) ; print $content['content_title'] ?>
            </li>
          </ul>

        </div>
        <?php endif;  ?>
      </td>
    
      <td> 
      Name: <?php print $item['comment_name'] ?><br>
      Email: <?php print $item['comment_email'] ?><br>
      Website: <?php print $item['comment_website'] ?><br>
      
      
      </td>
      <td><?php print (wordwrap($item['comment_body'],30)); ?></td>

      <td><?php print ($item['created_on']) ?></td>

      <td>
      <?php if($item['is_moderated'] == 'n') : ?>
      <span class="btn">
        <a href="javascript:;" onclick="<?php print $funcname_approve ?>('<?php print $item['id']; ?>')">Approve</a>
      </span>

      <?php endif;  ?>
      <span class="btn">
        <a href="javascript:;" onclick="<?php print $funcname_delete ?>('<?php print $item['id']; ?>')" >Delete</a>
      </span>
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
<?php else : ?>
<p><br />
<br />

No comments here!<br />
<br />

</p>
<?php endif; ?>
