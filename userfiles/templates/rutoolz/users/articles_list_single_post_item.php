<? $thumb = $this->users_model->getUserThumbnail( $the_post['created_by'], 80); ?>
<? $author = $this->users_model->getUserById($the_post['created_by']); ?>

<tr class="<?php echo alternator('odd', 'even') ?>" id="post-id-<? print $the_post['id']; ?>">
  <td valign="middle"><? if($layout == 'small_posts'): ?>
    <a style="margin-right: 10px;" class="left nextprev" href="javascript:parent.add_content_to_group('<? print $the_post['id']  ?>', '<?  print $the_post['content_title']; ?>');">Add</a> 
    <!--<input type="checkbox"  class="type-checkbox" />-->
    
    <?  endif; ?>
    <h3 class="mwboxtitle"> <a href="<? print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>">
      <?  print $the_post['content_title']; ?>
      </a> </h3></td>
  <td>Capture</td>
  <? /* Type  */ ?>
  <td><? print $the_post['updated_on']; ?></td>
  <? if($layout != 'small_posts'): ?>
 <!-- <td><span class="statusN">&nbsp;</span></td>-->
  <? endif; ?>
  <? ///if($the_post['content_description'] != ''): ?>
  <? //print (character_limiter($the_post['content_description'], 130, '...')); ?>
  <? // else: ?>
  <? // print character_limiter($the_post['content_body_nohtml'], 130, '...'); ?>
  <?  //endif; ?>
  <? if($show_edit_and_delete_buttons == true): ?>
  <td><a href="<? print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>" target="_blank" class="magnifier">&nbsp;</a></td>
  <? if($layout != 'small_posts'): ?>
  <td><a href="<? print site_url('users/user_action:post/id:').$the_post['id']; ?>" class="campaign-edit">&nbsp;</a></td>
  <td><a href="javascript:usersPostDelete('<? print $the_post['id']; ?>');" class="remove">&nbsp;</a></td>
  <? endif; ?>
  <?  endif; ?>
</tr>
