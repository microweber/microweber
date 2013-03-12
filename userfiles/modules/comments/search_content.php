<?
	$comments_data = array();
$comments_data['in_table'] =  'table_comments';
$comments_data['cache_group'] =  'comments/global';
if(isset($params['search-keyword'])){
	$comments_data['keyword'] =  $params['search-keyword'];
	//	$comments_data['debug'] =  'comments/global';
}
$data = get_content($comments_data);
?>
<? if(isarr($data )): ?>


<script type="text/javascript">
    mw.require("forms.js");
</script>
<script type="text/javascript">


    mw.adminComments = {
      action:function(form, val){
          var form = $(form);
          var field = form.find('.comment_state');
          field.val(val);

          var conf = true;
          if(val=='delete') var conf = confirm("<?php _e("Are you sure you want to delete this comment"); ?>?");

          if(conf){
              var id = form.attr('id');
              mw.form.post('#'+id, '<? print site_url('api/post_comment'); ?>');
              mw.reload_module('<? print $params['type'] ?>');
          }

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


    $(document).ready(function(){

        $(".comments-show-all").click(function(){
             var _new = mw.tools.firstParentWithClass(this, 'comment-post').querySelector('.new-comments');
             var _old = mw.tools.firstParentWithClass(this, 'comment-post').querySelector('.old-comments');
            $(_new).show();
            $(_old).show();
        });

        $(".comments-show-new").click(function(){
             var _new = mw.tools.firstParentWithClass(this, 'comment-post').querySelector('.new-comments');
             var _old = mw.tools.firstParentWithClass(this, 'comment-post').querySelector('.old-comments');
            $(_new).show();
            $(_old).hide();
        });

    });

</script>


<div>

  <? foreach($data  as $item){ ?>


  <?php

    $data = array(
        'content_id' => $item['id']
    );

    $comments = get_comments($data);



       // break;

    ?>

<div class="comment-post">
  <span class="img">
    <img src="<?php print thumbnail(get_picture($item['id']),67,67); ?>" alt="" />
    <span class="comments_number"><?php print sizeof($comments); ?></span>
  </span>
  <div class="comment-post-content-side">
    <h3><a href="javascript:;" class="mw-ui-link"><? print $item['title'] ?></a></h3>
    <a class="comment-post-url" href="<? print $item['url'] ?>"> <? print $item['url'] ?></a>
    <br>
    <a class="mw-ui-link" href="<? print $item['url'] ?>/editmode:y">Live edit</a>

  </div>

  <div class="comments-holder">
      <div class="new-comments">

      <?php $count_new = 0;  foreach ($comments as $comment){ ?>

          <?php if ($comment['is_moderated'] == 'n'){ $count_new ++; ?>


                <div class="comment-of-apost new-comment" id="comment-<?php print $comment['id']; ?>">

                    <a href="<?php print $comment['comment_website']; ?>" class="mw-ui-link"><?php print $comment['comment_name']; ?></a>
                        <p class="comment-body"><?php print $comment['comment_body']; ?></p>

                        <input type="hidden" name="id" value="<? print $comment['id'] ?>">
                        <input type="text" name="action" class="comment_state semi_hidden" />
                    <div class="manage-bar">
                     <div class="edit-comment">
                        <textarea name="comment_body"><?php print $comment['comment_body']; ?></textarea>
                        <a href="javascript:;" onclick="mw.adminComments.action(mwd.getElementById('comment-<?php print $comment['id'];?>'), 'update');" class="mw-ui-btn mw-ui-btn-small mw-ui-btn right">Update</a>
                        <div class="mw_clear"></div>
                     </div>
                     <span class="mw-ui-btn mw-ui-btn-small mw-ui-btn" onclick="mw.$('#comment-<?php print $comment['id'];?>').toggleClass('comment-edit-mode');">Edit</span>
                     <span class="mw-ui-btn mw-ui-btn-small mw-ui-btn-blue" onclick="mw.adminComments.action(mwd.getElementById('comment-<?php print $comment['id'];?>'), 'publish')">Publish</span>
                     <span class="mw-ui-btn mw-ui-btn-small mw-ui-btn-red" onclick="mw.adminComments.action(mwd.getElementById('comment-<?php print $comment['id'];?>'), 'delete')">Delete</span>
                    </div>

                </div>



           <?php } ?>
      <?php } ?>

      </div>
      <div class="old-comments">
    <?php  $count_old = 0; foreach ($comments as $comment){ ?>
      <?php if ($comment['is_moderated'] == 'y'){ $count_old ++; ?>

            <div class="comment-of-apost" id="comment-<?php print $comment['id']; ?>">

                   <a href="<?php print $comment['comment_website']; ?>" class="mw-ui-link"><?php print $comment['comment_name']; ?></a>
                   <p class="comment-body"><?php print $comment['comment_body']; ?></p>

                   <input type="hidden" name="id" value="<? print $comment['id'] ?>">
                        <input type="text" name="action" class="comment_state semi_hidden" />
                <div class="manage-bar">
                   <div class="edit-comment">
                    <textarea name="comment_body"><?php print $comment['comment_body']; ?></textarea>
                    <a href="javascript:;" onclick="mw.adminComments.action(mwd.getElementById('comment-<?php print $comment['id'];?>'), 'update');" class="mw-ui-btn mw-ui-btn-small mw-ui-btn right">Update</a>
                    <div class="mw_clear"></div>
                 </div>
                    <span class="mw-ui-btn mw-ui-btn-small mw-ui-btn" onclick="mw.$('#comment-<?php print $comment['id'];?>').toggleClass('comment-edit-mode');">Edit</span>
                     <span class="mw-ui-btn mw-ui-btn-small" onclick="mw.adminComments.action(mwd.getElementById('comment-<?php print $comment['id'];?>'), 'unpublish')">Unpublish</span>
                     <span class="mw-ui-btn mw-ui-btn-small mw-ui-btn-red" onclick="mw.adminComments.action(mwd.getElementById('comment-<?php print $comment['id'];?>'), 'delete')">Delete</span>
                   </div>
            </div>
       <?php } ?>
  <?php } ?>

 </div>
 </div>

    <div class="comments-show-btns">
      <span class="mw-ui-btn comments-show-all"><?php print ($count_old+$count_new); ?> All</span>
      <span class="mw-ui-btn mw-ui-btn-green comments-show-new"><?php print $count_new; ?> New</span>
    </div>
 </div>


    <?php // _d($item);  break;  ?>



  <? } ; ?>
</div>
<? endif; ?>
