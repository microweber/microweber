 <? if(!empty($posts_data['posts'])): ?>
          <? foreach ($posts_data['posts'] as $the_post): ?>
          <? $show_edit_and_delete_buttons = true; ?>
          <?php include ACTIVE_TEMPLATE_DIR."users/articles_list_single_post_item.php" ?>
          <? endforeach; ?>
          <? else : ?>
        <div class="post">
          <p> There are no posts here. Try again later. </p>
        </div>
        <? endif; ?>