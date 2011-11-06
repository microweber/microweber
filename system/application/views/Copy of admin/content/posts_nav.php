<div id="subheader">
    <ul>
        <li><a class="<?php if( $className == 'content' and $functionName == 'posts_manage')  : ?> active<?php endif; ?>" href="<?php print site_url('admin/content/posts_manage')  ?>">Manage all content</a></li>
        <li><a class="thickbox<?php if( $className == 'content' and $the_action == 'posts_add')  : ?> active<?php endif; ?>" href="#TB_inline?height=500&width=400&inlineId=create_new_content_choose_category_conpatiner&modal=false">Create new content</a></li>
       <?php if( $className == 'content' and $the_action == 'posts_edit')  : ?>
        <li><a class="ovalbutton<?php if( $className == 'content' and $the_action == 'posts_edit')  : ?> active<?php endif; ?>" href="<?php print site_url($this->uri->uri_string()); ?>"><?php print character_limiter($form_values['content_title'], 30, ' ') ; ?></a></li>
        <?php endif; ?>
    </ul>
</div>
<div id="message_area">
    <div id="message_wrapper">
        <div id="message" class="help_message">
        
        <?php if( $className == 'content' and $functionName == 'posts_manage')  : ?>
           To create new post please press the "Create new content" button and then select a category to put the content in it.
         <?php endif; ?>
         <?php if( $className == 'content' and (( $the_action == 'posts_edit') or ( $the_action == 'posts_add')))  : ?>
           While editing content remember that you must choose at least one category, also the content title and url fields are mandatory.
         <?php endif; ?>
        </div>
        <!-- /message -->
    </div><!-- /message_wrapper -->
</div><!-- /message_area -->

