<script type="text/javascript">
	
	function deleteUser($id){
		
		
		var answer = confirm("Sure?")
		if (answer){
		
		 window.location="<?php print site_url('admin/users/delete') ; ?>/id:"+$id;

		

	}
	else{
		return false;
	}
		
	}
	
	
	</script>
<?php if($content_pages_count > 1) :   ?>

<div align="center" id="admin_content_paging">
  <?php for ($i = 1; $i <= $content_pages_count; $i++) : ?>
  <a href="<?php print  $content_pages_links[$i]  ?>" <?php if($content_pages_curent_page == $i) :   ?> class="active" <?php endif; ?> ><?php print $i ?></a>
  <?php endfor; ?>
</div>
<?php endif; ?>
<form action="<?php print site_url('admin/users/users_do_search') ; ?>" method="post"  enctype="multipart/form-data" class="optionsForm">
  <div align="center">
    <input type="text" name="search_by_keyword" id="search_by_keyword" value="<?php print $search_by_keyword?>">
    &nbsp;
    <input type="submit" value="Search">
  </div>
</form>
<h2>Existing users</h2>
<?php foreach($users as $user ) : ?>
<form action="<?php print site_url('admin/users/index') ; ?>" method="post"  enctype="multipart/form-data" class="optionsForm">
  <input type="hidden" name="savedata" id="savedata" value="1">
  <table border="0" cellspacing="3" cellpadding="5" width="500">
    <tr>
      <th scope="col">ID</th>
      <th scope="col">Username</th>
      <th scope="col">Email</th>
      <th scope="col">Password</th>
      <th scope="col">Admin</th>
      <th scope="col">Active?</th>
      <th scope="col">Is Popular</th>
      <th scope="col">Details</th>
      <th scope="col">Save</th>
      <th scope="col">Delete</th>
    </tr>
    <tr>
      <td><?php print $user['id'] ?>
        <input name="id" type="hidden" value="<?php print $user['id'] ?>" /></td>
      <td><input name="username" type="text" value="<?php print $user['username'] ?>" /></td>
      <td><input name="email" type="text" value="<?php print $user['email'] ?>" /></td>
      <td><input name="password" type="password" value="<?php print $user['password'] ?>" /></td>
      <td><select name="is_admin">
          <option value="y" <?php if($user['is_admin'] == 'y') : ?> selected="selected" <?php endif; ?> >Yes</option>
          <option value="n" <?php if($user['is_admin'] == 'n') : ?> selected="selected" <?php endif; ?>>No</option>
        </select></td>
      <td><select name="is_active">
          <option value="y" <?php if($user['is_active'] == 'y') : ?> selected="selected" <?php endif; ?> >Yes</option>
          <option value="n" <?php if($user['is_active'] == 'n') : ?> selected="selected" <?php endif; ?>>No</option>
        </select></td>
      <td><select name="is_popular">
          <option value="n" <?php if($user['is_popular'] != 'y') : ?> selected="selected" <?php endif; ?>>No</option>
          <option value="y" <?php if($user['is_popular'] == 'y') : ?> selected="selected" <?php endif; ?> >Yes</option>
        </select></td>
      <td><a target="_blank" href="<?php print site_url('admin/users/edit/id:') ; ?><?php print $user['id'] ?>">Edit details</a></td>
      <td><input name="save" type="submit" value="save" /></td>
      <td><input name="delete" type="button" onclick="javascript:deleteUser('<?php print $user['id'] ?>')" value="delete" /></td>
    </tr>
  </table>
</form>
<br />
<hr />
<?php endforeach;  ?>
<br />
<h2><strong><a href="<?php print site_url('admin/users/edit') ?>/id:0">Add new user from here</a></strong></h2>
<hr />
