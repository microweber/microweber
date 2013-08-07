<?php

/*

  type: layout
  content_type: static
  name: Empty
  description: Empty layout

*/

?>
 
	 


<script type="text/javascript">
  mw.require('forms.js', true);
</script> 
	<script type="text/javascript">

    $(document).ready(function () {
        mw.$('#communuty-new-topic').bind("submit",function () {
            mw.utils.stateloading(true);
            mw.form.post(mw.$('#communuty-new-topic'), '<?php print mw_site_url('api') ?>/save_content', function () {
                mw.response('#new-topic-resp', this);

             
				 mw.utils.stateloading(false);

var $id  = this;
   
   $.get('<?php print mw_site_url('api_html/content_link/') ?>?id='+$id, function(data) {
     window.top.location.href = data;

   });





            });
            return false;
        });
		  mw.$('.mw_dropdown_topic_cat li').bind("click",function () {
 
 $('.topic_cat_val').html($(this).html());

 $('#topic-seleceted-category').val($(this).attr('value'));
		         
		  });
        mw.$(".mw-publish-forum-post").click(function(){
             mw.$('#communuty-new-topic').trigger("submit")
        });
    });
</script> 
 <?php  

$selected_cat = false;
 $url_cat = url_param('category');
if( $url_cat != false){
   $selected_cat = get_category_by_id($url_cat);   
   }
  ?>


	<div class="container main">
		<h4>Great, open new discusion. It is easy</h4>
		<br>
		<form action="#" method="post" id="communuty-new-topic">
			<div  class="mw_dropdown mw_dropdown_type_monster" data-value="-1"> 


			<span class="mw_dropdown_val_holder">
<?php if($url_cat == false): ?>
			 <span class="mw_dropdown_val topic_cat_val">Select a category</span> 
<?php else: ?>

				 <span class="mw_dropdown_val topic_cat_val"><?php print $selected_cat['title'] ?></span> 

<?php endif; ?>

			 <span class="dd_rte_arr"></span> 




			 </span>
				<div class="mw_dropdown_fields mw_dropdown_topic_cat">

						<?php $cats = category_tree('link={title}&rel_id='.CONTENT_ID.'&rel=content&users_can_create_content=y');  ?>
 

					 
				</div>
			</div>

			<input type="text" class="box"  name="title" placeholder="New Discusion - Title" style="width: 100%;" />


			<input type="hidden"  name="id"  value="0" />


			<input type="hidden"  name="category" id="topic-seleceted-category" <?php if($selected_cat != false): ?> value="<?php print $selected_cat['id'] ?>" <?php endif; ?> />
			<div class="box" id="topic-editor-holder">
				<textarea id="topic-editor" name="content"></textarea>
			</div>
			<script>
                  $(document).ready(function(){
                      var editor_area = mwd.getElementById('topic-editor');
                      mw.tools.iframe_editor(editor_area);
                  });
              </script>
			<div id="new-topic-footer">
				<!-- <label class="lcheck">
					<input type="checkbox" checked="checked" />
					<span>Notify me by e-mail on new answer</span></label> -->
					<div class="box-content pull-left">
            <img class="mw-captcha-img" src="<?php print mw_site_url('api/captcha') ?>" onclick="mw.tools.refresh_image(this);" />
          <input type="text" placeholder="<?php _e("Enter the captcha"); ?>" class="box" name="captcha">
        </div>

				<a href="javascript:;" class="mw-publish-forum-post orangebtn pull-right">Publish Topic</a> <!-- <a href="javascript:;" class="blue pull-right">Preview</a> --> </div>
		</form>




	</div>
	<div id="new-topic-resp">

</div>
	 