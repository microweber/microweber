<? $form_id = md5($comment_to_table.$comment_to_id); ?>

<div  id="<? print $form_id; ?>">
  <div >
    <form method="post" action="#" class="ajax_comment_form commentForm comment_form form" >
      <input type="hidden" name="to_table" value="<? print CI::model('core')->securityEncryptString($comment_to_table); ?>">
      <input type="hidden" name="to_table_id" value="<? print CI::model('core')->securityEncryptString($comment_to_id); ?>">
      <? if($display): ?>
      <input type="hidden" name="display" value="<?  print $display; ?>">
      <? endif; ?>
      <input type="hidden" name="update_element" value="#<? print $comments_update_element; ?>">
      <input type="hidden" name="hide_element" value="#<? print $form_id; ?>">
      <input type="hidden" name="show_element" value="#<? print $form_id; ?>_success">
      <span class="mw_area">
      <textarea name="comment_body" default="Write a comment..." class="required"></textarea>
      </span> <a class="submit mw_btn_x" href="javascript:void(0);"><span>Post comment</span></a>
    </form>
  </div>
</div>
<div id="<? print $form_id; ?>_success" style="display:none;"> Your comment is posted. </div>
