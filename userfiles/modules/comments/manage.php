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
    $(document).ready(function(){
        mw.$('.comments-manage-form').submit(function() {
            var id = $(this).attr('id');
			mw.form.post('#'+id, '<? print site_url('api/post_comment'); ?>');

           // mw.reload_module('#<? print $params['id'] ?>');
 mw.reload_module('<? print $params['type'] ?>');

            return false;
        });
    });
</script>

<div class="comments-manage" id="comments-manage-<? print $params['id'] ?>">
  <? foreach ($comments as $comment) : ?>
  <div class="comment" id="comment-<? print $comment['id'] ?>">
    <div class="comment-author"> <? print $comment['comment_name'] ?> </div>
    <div class="comment-body"> <? print $comment['comment_body'] ?> </div>
  </div>
  <form class="comments-manage-form" id="comments-form-<? print $comment['id'] ?>">
    <input type="hidden" name="id" value="<? print $comment['id'] ?>">
    Action:
    <select name="action">
      <? if($comment['is_moderated'] == 'n'): ?>
      <option value="publish">Publish</option>
      <? endif; ?>
      <? if($comment['is_moderated'] == 'y'): ?>
      <option value="unpublish">Unpublish</option>
      <? endif; ?>
      <option value="delete">Delete</option>
      <option value="spam">Spam</option>
    </select>
    <input type="submit"  value="submit">
  </form>
  <? endforeach; ?>
</div>
<? endif; ?>
