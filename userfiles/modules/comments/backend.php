<?php only_admin_access();?>
<script type="text/javascript">
    mw.require('<?php print $config['url_to_module'] ?>style.css', true);
    mw.require('color.js', true);

</script>
<script type="text/javascript">
mw.on.hashParam("search", function(){
    if(this  !== ''){
    	$('#mw_admin_posts_with_comments').attr('data-search-keyword',this);
    } else {
    	$('#mw_admin_posts_with_comments').removeAttr('data-search-keyword');
    }
	$('#mw_admin_posts_with_comments').removeAttr('content_id');
    mw.reload_module('#mw_admin_posts_with_comments', function(){
        mw.$(".mw-ui-searchfield, input[type='search']").removeClass('loading');
    });
});
mw.on.hashParam("content_id", function(){
        mw.$("a.comments-group").removeClass("active");
        mw.$("a[href*='content_id="+this+"']").addClass("active");
		if(this == 'settings'){
			mw.$('.comments-settings').show();
			mw.$('.comments-items').hide();
			mw.$('.comments-templates').hide();
		} else if(this == 'templates'){
			mw.$('.comments-settings').hide();
			mw.$('.comments-items').hide();
			mw.$('.comments-templates').show();
		} else {
			mw.$('.comments-settings').hide();
			mw.$('.comments-items').show();
			mw.$('.comments-templates').hide();
		}
    if(this  !== '' && this  != '0'){
		$('#mw_comments_admin_dashboard').hide();
		$('#mw_admin_posts_with_comments').show();
    	$('#mw_admin_posts_with_comments').attr('content_id',this);
		 mw.reload_module('#mw_admin_posts_with_comments', function(){
		 mw.adminComments.toggleMaster(mwd.querySelector('.comment-info-holder'));
    });
    } else {
    	mw.$('#mw_admin_posts_with_comments').removeAttr('content_id');
		mw.$('#mw_admin_posts_with_comments').removeAttr('rel_id');
	$('#mw_admin_posts_with_comments').removeAttr('rel_type');
		  mw.reload_module('#mw_admin_posts_with_comments');
    }
});

mw.on.hashParam("rel_id", function(){   
    if(this  !== '' && this  != '0'){
		mw.$('#mw_comments_admin_dashboard').hide();
		mw.$('#mw_admin_posts_with_comments').show();
    	mw.$('#mw_admin_posts_with_comments').attr('rel_id',this);
		mw.reload_module('#mw_admin_posts_with_comments', function(){
		mw.adminComments.toggleMaster(mwd.querySelector('.comment-info-holder'));
    });
    } else {
    	mw.$('#mw_admin_posts_with_comments').removeAttr('rel_id');
		mw.reload_module('#mw_admin_posts_with_comments');
    }
});

mw.on.hashParam("comments_for_content", function(){

});

</script>
<script type="text/javascript">
    mw.require("forms.js", true);
</script>
<script type="text/javascript">
    mw.adminComments = {
      action:function(form, val){
          var form = $(form);
          var field = form.find('.comment_state');
		  var connected_id = mw.$('[name="connected_id"]', form[0]).val();
          field.val(val);
          var conf = true;
          if(val=='delete'){var conf = confirm(mw.msg.to_delete_comment);}
          if(conf){
              var id = form.attr('id');
			  var data = form.serialize();
			  $.post( "<?php print api_link('post_comment'); ?>",data, function( data ) {
				   mw.reload_module('#mw_comments_for_post_'+connected_id, function(){
					  $('#mw_comments_for_post_'+connected_id).find(".comments-holder,.new-comments,.old-comments").show();
				  });
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
            mw.event.cancel(e);
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
		  if(master === null){
		    return false;
		  }
		  if(e != undefined){
                mw.event.cancel(e);
		  }
          var _new = master.parentNode.querySelector('.new-comments');
          var _old = master.parentNode.querySelector('.old-comments');
          if($(_new).is(":visible") || $(_old).is(":visible")){
               $([_new, _old]).hide();
               $(master).removeClass("active");
          }
          else{
              $([_new, _old]).show();
              $(master).addClass("active");
				var  is_cont = $(master).attr('content-id')
				if(typeof is_cont != "undefined"){
					var mark_as_old = {}
					mark_as_old.content_id = is_cont;
					$.post('<?php print api_link('mark_comments_as_old'); ?>', mark_as_old, function(data) {

					});
				}
         }
      }
    }
</script>
<?php $mw_notif =  (url_param('mw_notif'));
if( $mw_notif != false){
    $mw_notif = mw('notifications')->read( $mw_notif);
}
mw('notifications')->mark_as_read('comments');
 ?>
<?php if(is_array($mw_notif) and isset($mw_notif['rel_id'])): ?>
<script type="text/javascript">

$(document).ready(function(){
     $('#mw_admin_posts_with_comments').attr('rel_id',"<?php print $mw_notif['rel_id'] ?>");
     $('#mw_admin_posts_with_comments').attr('rel_type',"<?php print $mw_notif['rel_type'] ?>");
     mw.reload_module('#mw_admin_posts_with_comments', function(){
        mw.adminComments.toggleMaster(mwd.querySelector('.comment-info-holder'));
     });
});
</script>
<?php endif; ?>



		<div class="mw-ui-btn-nav"><a class="mw-ui-btn comments-group active" href="#content_id=0">
		<?php _e("Comments"); ?>
		</a> <a class="mw-ui-btn comments-group mw-ui-btn " href="#content_id=settings">
		<?php _lang("Settings","modules/comments"); ?>
		</a>
         
		<?php /*<a href="#content_id=templates" class="comments-group mw-ui-btn ">
		<?php _e("My templates"); ?>
		</a>*/ ?>
        </div>


	<div class="comments-tabs active">
		<div class="comments-tab comments-items" id="the_comments">
			<div id="comments-admin-side">
				<div class="comments-admin-header">
					<div class="comments-admin-header-info">
						<h2><?php _e("Comments"); ?></h2>
						<small>
						<?php _e("Read, moderate & publish comments"); ?>
						</small>
                    </div>
					<input
                          autocomplete="off"
                          type="search"
                          class="mw-ui-searchfield"
                          placeholder="<?php _e("Search comments"); ?>"
                          onkeyup="mw.form.dstatic(event);mw.on.stopWriting(this, function(){mw.url.windowHashParam('search', this.value)});"
                    />
				</div>
				<module type="comments/search_content" id="mw_admin_posts_with_comments"  />
			</div>
		</div>
		<div class="comments-tab comments-settings" style="display: none">
			<module type="comments/settings" id="mw_admin_comments_settings"  />
		</div>
		<?php /*<div class="comments-tab comments-templates" style="display: none">
			<div class="comments-admin-header">
				<div class="comments-admin-header-info">
					<module type="admin/templates/browse" for="<?php print $config["the_module"] ?>"  />
				</div>
			</div>
		</div>*/ ?>
	</div>

