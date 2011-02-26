  <script type="text/javascript">
function deleteThisPost(){


var answer = confirm("Are you sure?")
	if (answer){
	//""
	window.location = '<?php print site_url('admin/content/posts_delete') ?>/id:<?php print $form_values['id']; ?>'
	}
	else{

	}

}

   
</script>
<!--<a class="objbigSave right" href="javascript:;">Save</a>   -->


  <?php if($form_values['id'] != 0) : ?>
  <a href="javascript:deleteThisPost()" class="delete_post">delete this post</a>
  <?php endif; ?>
  <div class="clear"></div>
  <br />
  <br />
</form>