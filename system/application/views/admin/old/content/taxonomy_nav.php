<div id="subheader">
    <ul>
      <li><a class="ovalbutton<?php if( $className == 'content' and $functionName == 'taxonomy_categories')  : ?> active<?php endif; ?>"
      href="<?php print site_url('admin/content/taxonomy_categories')  ?>">
      <span><img src="<?php print_the_static_files_url() ; ?>/icons/folder__pencil.png" alt=" " border="0">Categories</span>
      </a></li>

<!--      <li><a class="ovalbutton<?php if( $className == 'content' and $the_action == 'taxonomy_tags')  : ?> active<?php endif; ?>" href="<?php print site_url('admin/content/taxonomy_tags')  ?>"><span><img src="<?php print_the_static_files_url() ; ?>/icons/tag_blue_edit.png" alt=" " border="0">Tags</span></a></li>
      <?php if( $className == 'content' and $the_action == 'posts_edit')  : ?>
      <li><a class="ovalbutton<?php if( $className == 'content' and $the_action == 'posts_edit')  : ?> active<?php endif; ?>" href="<?php print site_url('admin/content/posts_edit/id:')  ?>/<?php print $form_values['id'];?>"><img src="<?php print_the_static_files_url() ; ?>/icons/document__pencil.png" alt=""><?php print character_limiter($form_values['content_title'], 30, ' ') ; ?></a></li>
      <?php endif; ?>-->
    </ul>
</div>