<script type="text/javascript">

$(document).ready(function() {



     $("#content_url").addClass("required")







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



<label class="lbl">Content Title:  </label>

<div id="content_title_wrapper">



      <input class="required" style="width:500px" name="content_title" id="content_title" type="text" value="<?php print $form_values['content_title']; ?>">

        <b>

  <!--  -->

  </b> </div>

<small class="urlt">url: </small>      <span id="content_url_demo_holder"><?php print site_url() ?></span>



<input name="content_url"

                 id="content_url" type="text"  value="<?php print $form_values['content_url']; ?>"

                 <?php if($form_values['content_url'] == ''): ?> class="disabled"  <?php endif; ?>

                  style="padding-left:0px"   />

<span class="penico">&nbsp;</span>

<div style="height: 2px;overflow: hidden;clear: both"></div><br />


    <fieldset>

    <legend>Page description</legend>

  <textarea name="content_description" id="content_description"><?php print $form_values['content_description']; ?></textarea>  

    </fieldset>

      

      <br />



   <label class="lbl">Content Body:  </label>   

 <textarea name="content_body" id="content_body" class="richtext" style="width: 500px;height:120px "><?php print $form_values['content_body']; ?></textarea>

 

  





<br />

<br />

<br />



<small>Content Body Filename</small>



<input  name="content_body_filename" id="content_body_filename" type="text" value="<?php print $form_values['content_body_filename']; ?>">

 

 

    

      

      



      

      

      

      

      

      

   



    

