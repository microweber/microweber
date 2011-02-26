
<h2 style="padding-bottom: 5px">
  <? if($form_title): ?>
  <? print $form_title  ?>
  <? else : ?>
  Post your comment
  <? endif; ?>
</h2>
<? $update_element = 'comments_list_'.md5(serialize($post_id));
  $this->template ['comments_update_element'] = $update_element;
	$this->load->vars ( $this->template );
  ?>
<? if(user_id() != 0): ?>
<? comment_post_form($post_id)  ?>
<? else: ?>
Login to post comments.
<? endif; ?>
<div class="c"></div>
<div id="<? print $update_element ?>">
  <? if($list_title): ?>
  <h2 style="padding-bottom: 5px"><? print $list_title  ?></h2>
  <? else : ?>
  <h2 style="padding-bottom: 5px">Comments</h2>
  <? endif; ?>
  <? comments_list($post_id)  ?>
</div>
