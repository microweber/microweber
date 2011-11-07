
<div class="toys_list_item post_list" id="post-<? print $the_post['id'];  ?>">

<a href="<? print post_link($the_post['id']); ?>" class="mw_blue_link"><? print $the_post['content_title'];  ?></a> 
<a href="<? print post_link($the_post['id']); ?>" class="img" style="background-image:url(<? print thumbnail($the_post['id'], 150) ?>)"> </a> 

<? if(intval($the_post['custom_fields']['price']) != 0): ?>
Price: $<? print $the_post['custom_fields']['price'] ?> 
<? endif; ?>
 
 

 
 <? if(url_param('action') == 'toys'): ?>
 <? if(($the_post['created_by']) == user_id()): ?>
  <a href="javascript:add_edit_toy('<? print $the_post['id'];  ?>');" class="mw_btn_s right"><span>Edit</span></a>
 
 
 
 <?  endif; ?>
 <? endif; ?>

</div>
