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



	<div class="container main">
		<h4>Great, open new discusion. It is easy</h4>
		<br>
		<form action="#" method="post" id="communuty-new-topic">
			<div  class="mw_dropdown mw_dropdown_type_monster" data-value="-1"> <span class="mw_dropdown_val_holder"> <span class="mw_dropdown_val topic_cat_val">Select a category</span> <span class="dd_rte_arr"></span> </span>
				<div class="mw_dropdown_fields mw_dropdown_topic_cat">

						<?php $cats = category_tree('link={title}&rel_id='.CONTENT_ID.'&rel=content&users_can_create_content=y');  ?>
 

					 
				</div>
			</div>

			<input type="text" class="box"  name="title" placeholder="New Discusion - Title" style="width: 100%;" />




			<input type="hidden"  name="category" id="topic-seleceted-category" />
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
				<label class="lcheck">
					<input type="checkbox" checked="checked" />
					<span>Notify me by e-mail on new answer</span></label>
				<a href="javascript:;" class="mw-publish-forum-post orangebtn pull-right">Publish Topic</a> <a href="javascript:;" class="blue pull-right">Preview</a> </div>
		</form>


<div id="new-topic-resp">

</div>

	</div>
	 