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
		  var connected_id = form.find('[name="connected_id"]').val();
          field.val(val);
          var conf = true;
          if(val=='delete'){var conf = confirm(mw.msg.to_delete_comment);}
          if(conf){
              var id = form.attr('id');
              mw.form.post('#'+id, '<? print site_url('api/post_comment'); ?>');
			  mw.reload_module('#mw_comments_for_post_'+connected_id, function(){
				  $('#mw_comments_for_post_'+connected_id).find(".comments-holder,.new-comments,.old-comments").show();
			  });
          }
      },
      toggleEdit:function(id){
        mw.$(id).toggleClass('comment-edit-mode');
        if(mw.$(id).hasClass("comment-edit-mode")){
             mw.$(id).find("textarea").focus();
        }
      },
      display:function(e, el, what){
            mw.e.cancel(e);
            var _new = mw.tools.firstParentWithClass(el, 'comment-post').querySelector('.new-comments');
            var _old = mw.tools.firstParentWithClass(el, 'comment-post').querySelector('.old-comments');
            if(what=='all'){
                $(_new).show();
                $(_old).show();
            }
            else if(what=='new'){
               $(_new).show();
               $(_old).hide();
            }
      },
      toggleMaster:function(master, e){
          mw.e.cancel(e);
          var _new = master.parentNode.querySelector('.new-comments');
          var _old = master.parentNode.querySelector('.old-comments');
          if($(_new).is(":visible") || $(_old).is(":visible")){
               $([_new, _old]).hide();
               $(master).removeClass("active");
          }
          else{
              $([_new, _old]).show();
              $(master).addClass("active");
          }
      }
    }




</script>

<div>
  <? foreach($data  as $item){ ?>

    <module type="comments/comments_for_post" id="mw_comments_for_post_<? print $item['id'] ?>" content_id="<? print $item['id'] ?>" >

  <?php // _d($item);  break;  ?>
  <? } ; ?>
</div>
<? endif; ?>
