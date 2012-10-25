<?php
/*

type: layout
name: Online shop
is_shop: yes
description: shop

*/

?>
<? include TEMPLATE_DIR. "header.php"; ?>

<section id="river">
  <article id="post-<? print POST_ID ?>" class="post">
    <h2 class="edit no-drop"    rel="content"  data-field="title"  data-id="<? print POST_ID ?>"  >My post title</h2>
    <div class="edit"  rel="content"  data-field="description" data-id="<? print POST_ID ?>"  >
      <p>My post description</p>
    </div>
    <div class="edit"  rel="content"  data-field="content" data-id="<? print POST_ID ?>"  >
      <p>My post content</p>
    </div>
    
    
    
    
<script type="text/javascript">

    mw.require("forms.js");
</script> 
<script type="text/javascript">
    $(document).ready(function(){
        mw.$('.comments-manage-form').submit(function() {
            var id = $(this).attr('id');
			mw.form.post('#'+id, 'http://pecata/1k/api/post_comment');

           // mw.reload_module('#comments-manage-20121025082001');
 mw.reload_module('comments/manage');

            return false;
        });
    });
</script>

 <module type="comments/manage2"  is_moderated="n" />

    
    
    
    
    
    
  </article>
  <module data-type="comments" id="comments_posts" data-content-id="<? print POST_ID ?>"  />
</section>
<? include   TEMPLATE_DIR.  "footer.php"; ?>
