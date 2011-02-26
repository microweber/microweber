 

<?php if(empty($files)  ): ?>



Please upload some files in the files.

<?php else : ?>

<script type="text/javascript">

 

  

  

  

  

  // prepare the form when the DOM is ready 

$(document).ready(function() { 

    var media_fils_options = { 

      //  target:        '#output1',   // target element(s) to be updated with server response 

        beforeSubmit:  media_fils_showRequest,  // pre-submit callback 

		type:      'post',

		url:       '<?php print site_url('admin/media/mediaSave') ?>',

		clearForm: true,

		resetForm: true   ,

        success:       media_fils_showResponse  // post-submit callback 

 

        // other available options: 

        //url:       url         // override for form's 'action' attribute 

        //type:      type        // 'get' or 'post', override for form's 'method' attribute 

        //dataType:  null        // 'xml', 'script', or 'json' (expected server response type) 

        //clearForm: true        // clear all form fields after successful submit 

        //resetForm: true        // reset the form after successful submit 

 

        // $.ajax options can be used here too, for example: 

        //timeout:   3000 

    }; 

 

    // bind form using 'ajaxForm' 

    $('.filsAjaxSaveForm').ajaxForm(media_fils_options); 

}); 

 

// pre-submit callback 

function media_fils_showRequest(formData, jqForm, options) { 

    // formData is an array; here we use $.param to convert it to a string to display it 

    // but the form plugin does this for you automatically when it submits the data 

   // 

   var queryString = $.param(formData); 

  // alert(queryString);

  //  alert(formData);

   

 $('#saving-files').fadeIn();

    // jqForm is a jQuery object encapsulating the form element.  To access the 

    // DOM element for the form do this: 

    // var formElement = jqForm[0]; 

// var formElement = jqForm[0]; 

 //formElement.fadeOut();

   // alert('About to submit: \n\n' + queryString); 

 

    // here we could return false to prevent the form from being submitted; 

    // returning anything other than false will allow the form submit to continue 

    return true; 

} 

 

// post-submit callback 

function media_fils_showResponse(responseText, statusText)  { 

    // for normal html responses, the first argument to the success callback 

    // is the XMLHttpRequest object's responseText property 

	$('.sortable_fileo_objects_holder').html('<img src="<?php print_the_static_files_url() ; ?>ajax-loader.gif" align="left" alt="Working..." /> Reloading files...'); 

   $('#saving-files').fadeOut();

   

   

   

 file_uploadComplete();

 //$('#media_edit_table_'+responseText).fadeOut();

 

// $('#fil_saved_txt_'+responseText).fadeIn('slow', function(){

  //  $('#fil_saved_txt_'+responseText).fadeOut();

	

 //});









  //$('#media_edit_table_'+responseText).fadeIn();



 

    // if the ajaxForm method was passed an Options Object with the dataType 

    // property set to 'xml' then the first argument to the success callback 

    // is the XMLHttpRequest object's responseXML property 

 

    // if the ajaxForm method was passed an Options Object with the dataType 

    // property set to 'json' then the first argument to the success callback 

    // is the json data object returned by the server 

 

   // alert('status: ' + statusText + '\n\nresponseText: \n' + responseText + 

      //  '\n\nThe output div should have already been updated with the responseText.'); 

} 

</script>

<br />



 <div id="saving-files" style="display:none"><img src="<?php print_the_static_files_url() ; ?>ajax-loader.gif" align="left" alt="Working..." /> Saving... please wait</div>

<br />



<ul id="sortable_fileo_objects">

  <?php $i = 1; if(!empty($files)): ?>

  <?php foreach($files as $fil): ?>

  <li id="files_module_sortable_fils_positions_<?php print $fil['id'] ?>">

    <h5 class="sortable_fileo_objects_title">

      <?php if(strval($fil['media_name'] != '')): ?>

      <?php print $fil['media_name'] ?>

      <?php else: ?>

      <?php print $fil['filename'] ?>

      <?php endif; ?>

    </h5>

    <div class="sortable_fileo_objects_holder">

      <table width="100%" cellspacing="1" cellpadding="1">

        <tr>

         

          <td>
          <? print $fil['url']  ?>
          <br />


          <?php //print $this->core_model->mediaGetThumbnailForMediaId($fil['id'], '200');	?>

          <form action=""   class="filsAjaxSaveForm" method="post" enctype="multipart/form-data">

              <input name="id" type="hidden" value="<?php print $fil['id'] ?>" />

              

               

              

              <table cellspacing="1" cellpadding="1" width="100%" class="fileo_details_table" id="media_edit_table_<?php print $fil['id'] ?>" >

                <!-- <tr>

                  <td><h4>Filename: <?php print character_limiter( $fil['filename'], 10) ?></h4>

                    </td>

                </tr>-->

                <tr>

                  <td><b>Title:</b>

                    <input name="media_name" type="text" value="<?php print $fil['media_name'] ?>" /></td>

                </tr>

                <tr>

                  <td><b>Description:</b>

                    <textarea name="media_description" cols="" rows=""><?php print $fil['media_description'] ?></textarea></td>

                </tr>

                
<!--
                <tr>

                  <td><b>Thumbnail:</b>

                     <input name="picture_0" type="file" /></td>

                </tr>-->

                

              </table>

              <div class="changes-are-saved" id="fil_saved_txt_<?php print $fil['id'] ?>" style="display:none"> Changes are saved... </div>

              <input name="save" style="float: left;margin-left:249px; " type="submit" value="save" />

            </form>

           

            

            </td>

        </tr>

      </table>

      <a href="javascript:;" class="sortable_fileo_objects_delete" onClick="contentMediaDeletefile('<?php print $fil['id'] ?>')">delete</a> 
      <br />

      
      
      </div>
      <hr />

    <!-- <span class="fil_obj_New"></span>-->

  </li>

  <?php $i++; endforeach; ?>

  <?php endif; ?>

</ul>

<?php endif; ?>

<br />

<br />

<br />

<br />

<br />

<br />

<br />

