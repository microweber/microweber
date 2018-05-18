<?php
return print('This file is deprecated ' . __FILE__);


 if(is_admin() == false){
	 mw_error('Must be admin');
 }
 $data= array();
 if(isset($data['id'])){
	// unset($data['id']);
 }
 
 if(!isset($params['is_moderated'])){
	$data['is_moderated'] = 0;
 } else {
	//  $is_moderated = 'n';
 }
 
  if(isset($params['content-id'])){
	$data['rel_type'] = 'content';
	$data['rel_id'] = intval($params['content-id']);
  }

 $comments = get_comments($data);

?>

<script type="text/javascript">

    mw.require('<?php print $config['url_to_module'] ?>comments_admin.js');
</script>




<?php if (is_array($comments)): ?>
<script type="text/javascript">
    mw.require("forms.js",true);
</script>
<script type="text/javascript">


    mw.adminComments = {
      action:function(form, val){
		    var form_id = form;
          var form = $(form);
          var field = form.find('.comment_state');
          field.val(val);
          var id = form.attr('id');
		   
          mw.form.post('#'+id, '<?php print api_link('post_comment'); ?>');
          //mw.reload_module('<?php print $params['type'] ?>');
      },
      edit:function(form){
        var body = form.querySelector('.comment-body-edit');
        var comment =  form.querySelector('.comment-body')
        var arr = [body, comment];
        $(body).find('textarea').css({
            minHeight:$(comment).height(),
            minWidth:$(comment).width()
        });
        mw.tools.el_switch(arr);
      },
      save:function(form){

      }
    }




</script>

<div class="mw-comments-manage" id="comments-manage-<?php print $params['id'] ?>">
  <?php foreach ($comments as $comment) : ?>
  <div class="mw-comment-body">
    <form class="comments-manage-form" id="comments-form-<?php print $comment['id'] ?>">
      <div class="comment-author"><?php print $comment['comment_name'] ?></div>
      <div class="comment-body"><?php print strip_tags($comment['comment_body']) ?> <a href="javascript:;" class="mw-ui-link" onclick="mw.adminComments.edit(mw.tools.firstParentWithClass(this, 'comments-manage-form'))">
        <?php _e("Edit"); ?>
        </a></div>
      <div class="comment-body-edit">

        <textarea name="comment_body"><?php print ($comment['comment_body']); ?></textarea>
        <span class="mw-ui-btn pull-right"><?php _e("Save"); ?></span>

        </div>
      <input type="hidden" name="id" value="<?php print $comment['id'] ?>">
      <input type="hidden" name="action" class="comment_state" value="<?php print $comment['id'] ?>" />
      <div class="comment-controll-bar"> <a href="javascript:;" class="mw-ui-btn" onclick="">
        <?php _e("Reply"); ?>
        </a>
        <?php if($comment['is_moderated'] == 0): ?>
        <a href="javascript:;" class="mw-ui-btn" onclick="mw.adminComments.action('#comments-form-<?php print $comment['id'] ?>', 'publish')">
        <?php _e("Publish"); ?>
        </a>
        <?php endif; ?>
        <?php if($comment['is_moderated'] == 1): ?>
        <a href="javascript:;" class="mw-ui-btn" onclick="mw.adminComments.action('#comments-form-<?php print $comment['id'] ?>', 'unpublish')">
        <?php _e("Unpublish"); ?>
        </a>
        <?php endif; ?>
        <a href="javascript:;" class="mw-ui-btn" onclick="mw.adminComments.action('#comments-form-<?php print $comment['id'] ?>', 'delete')">
        <?php _e("Delete"); ?>
        </a> <a href="javascript:;" class="mw-ui-btn" onclick="mw.adminComments.action('#comments-form-<?php print $comment['id'] ?>', 'spam')">
        <?php _e("Mark as spam"); ?>
        </a> </div>
    </form>
  </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>
