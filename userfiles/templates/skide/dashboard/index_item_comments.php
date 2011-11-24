<? $form_id = md5($comment_to_table.$comment_to_id); ?>

<div class="comment_area" id="<? print $form_id; ?>">
  <div class="comment_area_content">
    <form method="post" action="#" class="ajax_comment_form">
      <input type="hidden" name="to_table" value="<? print CI::model('core')->securityEncryptString($comment_to_table); ?>">
      <input type="hidden" name="to_table_id" value="<? print CI::model('core')->securityEncryptString($comment_to_id); ?>">
      <input type="hidden" name="display" value="<? print CI::model('core')->securityEncryptString(('dashboard/index_item_comments_list.php')); ?>">
      <input type="hidden" name="update_element" value="#<? print $comments_update_element; ?>">
      <input type="hidden" name="hide_element" value="#<? print $form_id; ?>">
      <input type="hidden" name="show_element" value="#<? print $form_id; ?>_success">
      <a href="#" class="user_photo" style="background-image: url('<? print user_thumbnail(user_id(), '75') ?>')"></a>
      <span class="mw_area">
           <textarea name="comment_body" default="Write a comment..." class="required">Write a comment...</textarea>
      </span>
      <a href="#" class="submit">Submit</a>
    </form>
  </div>
</div>

<div id="<? print $form_id; ?>_success" style="display:none;"> Your comment is posted. </div>
