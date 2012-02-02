<table  border="0" cellspacing="5" cellpadding="5" class="plugins_subnav">
  <tr>
    <td>
    <a class="ovalbutton<?php if( $className == 'content' and $functionName == 'posts_manage')  : ?> active<?php endif; ?>" href="<?php print site_url('admin/content/posts_manage')  ?>"><span><img src="<?php print_the_static_files_url() ; ?>/icons/documents_stack.png" alt=" " border="0">Manage all content</span></a>
    </td>
    
    
    
    
    <td>
    <a class="ovalbutton<?php if( $className == 'content' and $the_action == 'posts_add')  : ?> active<?php endif; ?>" href="<?php print site_url('admin/content/posts_edit/id:0')  ?>"><span><img src="<?php print_the_static_files_url() ; ?>/icons/document__plus.png" alt=" " border="0">Create new content</span></a>
    </td>
    
   <?php if( $className == 'content' and $the_action == 'posts_edit')  : ?>
    
    
      <td>
    <a class="ovalbutton<?php if( $className == 'content' and $the_action == 'posts_edit')  : ?> active<?php endif; ?>" href="<?php print site_url('admin/content/posts_edit/id:')  ?>/<?php print $form_values['id'];?>"><span><img src="<?php print_the_static_files_url() ; ?>/icons/document__pencil.png" alt=" " border="0"><?php print character_limiter($form_values['content_title'], 30, ' ') ; ?></span></a>
    </td>
    
    
    <?php endif; ?>
    
  </tr>
</table>