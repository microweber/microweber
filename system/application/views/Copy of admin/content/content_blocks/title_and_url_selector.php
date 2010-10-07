<?php


//	$sections = $this->content_model->getBlogSections();
///var_Dump($sections);


?>
<script type="text/javascript">
$(document).ready(function() {





$("#content_url").dblclick( function () {
     $("#content_url").toggleClass('disabled');
    });






});






//$("button:gt(1)").attr("disabled","disabled");

	 $(document).ready(function() {




$("#content_title").handleKeyboardChange(900).change(function()
{



if (document.getElementById("content_url_demo_holder") != null){
			   		//lets get the url

					if($("#content_url").hasClass("disabled") == true){
									$.post("<?php print site_url('ajax_helpers/content_GenerateUniqueUrlTitleFromContentTitle') ?>", {
									content_title: $("#content_title").val(),
									id: $("#id").val()
									 },
									  function(data1){
									   //$("#content_url_demo_holder").html(data1);
									   //$("#content_url").val($("#content_url_demo_holder").text() + "/" + data1);
									   $("#content_url").val(data1);
									  });
							   }
                       }
});
});


  function autogrow(item){
        $("#autoWidthTest").remove();
        var i_val = $(item).val();

        $("body").append("<span id='autoWidthTest' style='position:absolute;left:-10000px;'>" + i_val + "</span>");
        if($("#autoWidthTest").width()<350){
          $(item).css("width", $("#autoWidthTest").width() + 10);
        }


  }
  setInterval("autogrow('#content_url')", 550);

	</script>


<label class="lbl required" style="padding-top: 17px;width:200px; float: left">Title</label>
<div id="content_title_wrapper">
  <input name="content_title" id="content_title" class="required" type="text" value="<?php print $form_values['content_title']; ?>"   style="width:615px;" />
  <b>
  <!--  -->
  </b> </div>

<small class="urlt">url: </small><span id="content_url_demo_holder"></span> <span class="objrarr">/</span>
<input name="content_url"
                 id="content_url" type="text"  value="<?php print $form_values['content_url']; ?>"
                 <?php if($form_values['content_url'] == ''): ?> class="disabled required"  <?php endif; ?>
                  style="padding-left:0px"   />
<span class="penico">&nbsp;</span>
<div style="height: 2px;overflow: hidden;clear: both"></div>
<!--<label class="lbl">Is featured:</label>
<select name="is_featured" id="is_featured">
  <option  <?php if($form_values['is_featured'] == 'n' ): ?> selected="selected" <?php endif; ?>  value="n">no</option>
  <option  <?php if($form_values['is_featured'] == 'y' ): ?> selected="selected" <?php endif; ?>  value="y">yes</option>
</select>-->
