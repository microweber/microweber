<? $edit = url_param('edit'); ?>

<nav id="secondary">
  <ul>
    <li <? if($edit == false): ?> class="current" <? endif; ?>><a href="#maintab">Active ads</a></li>
    <li <? if($edit != false): ?> class="current" <? endif; ?>><a href="#secondtab">Post new</a></li>
  </ul>
</nav>
<!-- The content -->
<section id="content_tabs">
  <div class="tab" id="maintab">
    <h2>My job ads</h2>
    <? $posts = get_posts('items_per_page=5000&created_by='.user_id());
	
	// p( $posts);
	
	?>
    <? if(!empty($posts)): ?>
    <table class="datatable">
      <thead>
        <tr>
          <th>Title</th>
          <th>Description</th>
          <th>Candidates</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <? foreach($posts['posts'] as $post): ?>
        <tr id="post_id_<?  print $post['id'] ?>">
          <td><? //p( $post); ?>
            <?  print $post['content_title_nohtml'] ?></td>
          <td><?  print character_limiter($post['content_body_nohtml'], 100) ?></td>
          <td><? //p( $post); ?>
            <?  print comments_count($post['id'], $is_moderated = false, $for = 'post')  ?>
            <a href="<? print post_link($post['id']) ?>" class="button icon user">View</a></td>
          <td><span class="button-group"> <a href="<? print site_url('members/view:posts/edit:') ?><?  print $post['id'] ?>" class="button icon edit">Edit</a> <a href="javascript:mw.content.del('<?  print $post['id'] ?>', '#post_id_<?  print $post['id'] ?>')" class="button icon remove danger">Remove</a> </span></td>
        </tr>
        <? endforeach; ?>
      </tbody>
    </table>
    <? endif; ?>
    <div class="clear"></div>
  </div>
  <div class="tab" id="secondtab">
    <? include('post_edit.php'); ?>
    <div class="clear"></div>
  </div>
</section>
