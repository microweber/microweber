<?
 
 if(is_admin() == false){
	 error('Must be admin');
 }
 $data= array();
 if(isset($data['id'])){
	// unset($data['id']);
 }
 
 if(!isset($params['is_moderated'])){
	$data['is_moderated'] = 'n';
 } else {
	//  $is_moderated = 'n';
 }
 
  if(isset($params['content-id'])){
	$data['to_table'] = 'table_content';
	$data['to_table_id'] = intval($params['content-id']);
  }

 $comments = get_comments($data);

?>
<? if (isarr($comments)): ?>
<script  type="text/javascript">
    mw.require("forms.js");
</script>
<script  type="text/javascript">


    mw.adminComments = {
      action:function(form, val){
          var form = $(form);
          var field = form.find('#comment_state');
          field.val(val);
          var id = form.attr('id');
          mw.form.post('#'+id, '<? print site_url('api/post_comment'); ?>');
          mw.reload_module('<? print $params['type'] ?>');
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



<div class="mw-comments-manage" id="comments-manage-<? print $params['id'] ?>">



  <? foreach ($comments as $comment) : ?>

      <div class="mw-comment-body">
        <form class="comments-manage-form" id="comments-form-<? print $comment['id'] ?>">
          <div class="comment-author"><? print $comment['comment_name'] ?></div>
          <div class="comment-body"><? print $comment['comment_body'] ?> <a href="javascript:;" class="mw-ui-link" onclick="mw.adminComments.edit(mw.tools.firstParentWithClass(this, 'comments-manage-form'))"><?php _e("Edit"); ?></a></div>
          <div class="comment-body-edit">
            <textarea name="comment_body"><? print $comment['comment_body'] ?></textarea>
            <span class="mw-ui-btn"><?php _e("Save"); ?></span>
          </div>


            <input type="hidden" name="id" value="<? print $comment['id'] ?>">
            <input type="hidden" name="action" id="comment_state" value="<? print $comment['id'] ?>" />

          <div class="comment-controll-bar">
              <a href="javascript:;" class="mw-ui-btn left" onclick=""><?php _e("Reply"); ?></a>

              <? if($comment['is_moderated'] == 'n'): ?>
                <a href="javascript:;" class="mw-ui-btn" onclick="mw.adminComments.action(this.parentNode, 'publish')"><?php _e("Publish"); ?></a>
              <? endif; ?>
              <? if($comment['is_moderated'] == 'y'): ?>
                <a href="javascript:;" class="mw-ui-btn" onclick="mw.adminComments.action(this.parentNode, 'unpublish')"><?php _e("Unpublish"); ?></a>
              <? endif; ?>
              <a href="javascript:;" class="mw-ui-btn" onclick="mw.adminComments.action(this.parentNode, 'delete')"><?php _e("Delete"); ?></a>
              <a href="javascript:;" class="mw-ui-btn" onclick="mw.adminComments.action(this.parentNode, 'spam')"><?php _e("Mark as spam"); ?></a>
          </div>
          </form>
      </div>

  <? endforeach; ?>


</div>
<? endif; ?>
