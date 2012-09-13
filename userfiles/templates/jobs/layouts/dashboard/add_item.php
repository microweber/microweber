<script type="text/javascript">
$(document).ready(function(){
    

    $(".new_album_btn").click(function(){

    var new_album_pop = modal.init({
        width:600,
        height:'auto',
        customPosition:{
          left:'center',
          top:$(window).scrollTop()+70
        },
        html:$('#pic_upload1')
    });
    $(new_album_pop).css({
      height:'auto',
    });

        modal.overlay();

    });


});










$(document).ready(function() { 
    var options = { 
       url:       '<? print site_url('api/content/save_post'); ?>',
	   type: 'post',
	   
	   //target:        '#output2',   // target element(s) to be updated with server response 
        beforeSubmit:  showRequest,  // pre-submit callback 
		
        success:       showResponse  // post-submit callback 
 
        // other available options: 
        //url:       url         // override for form's 'action' attribute 
        //type:      type        // 'get' or 'post', override for form's 'method' attribute 
        //dataType:  null        // 'xml', 'script', or 'json' (expected server response type) 
        //clearForm: true        // clear all form fields after successful submit 
        //resetForm: true        // reset the form after successful submit 
 
        // $.ajax options can be used here too, for example: 
        //timeout:   3000 
    }; 
 
    // bind to the form's submit event 
    $('#pic_upload1').submit(function() { 
        // inside event callbacks 'this' is the DOM element so we first 
        // wrap it in a jQuery object and then invoke ajaxSubmit 
        $(this).ajaxSubmit(options); 
 
        // !!! Important !!! 
        // always return false to prevent standard browser submit and page navigation 
        return false; 
    }); 
}); 
 
// pre-submit callback 
function showRequest(formData, jqForm, options) { 
    // formData is an array; here we use $.param to convert it to a string to display it 
    // but the form plugin does this for you automatically when it submits the data 
    var queryString = $.param(formData); 
 
    // jqForm is a jQuery object encapsulating the form element.  To access the 
    // DOM element for the form do this: 
    // var formElement = jqForm[0]; 
 
   // alert('About to submit: \n\n' + queryString); 
 
    // here we could return false to prevent the form from being submitted; 
    // returning anything other than false will allow the form submit to continue 
    return $("#pic_upload1").isValid();; 
} 
 
// post-submit callback 
function showResponse(responseText, statusText, xhr, $form)  { 
    // for normal html responses, the first argument to the success callback 
    // is the XMLHttpRequest object's responseText property 
  modal.close();
  
  window.location.reload(true);
    // if the ajaxSubmit method was passed an Options Object with the dataType 
    // property set to 'xml' then the first argument to the success callback 
    // is the XMLHttpRequest object's responseXML property 
 
    // if the ajaxSubmit method was passed an Options Object with the dataType 
    // property set to 'json' then the first argument to the success callback 
    // is the json data object returned by the server 
 
   // alert('status: ' + statusText + '\n\nresponseText: \n' + responseText +    '\n\nThe output div should have already been updated with the responseText.'); 
} 





</script>
<? $the_post = get_post(url_param('id')); ?>
 <? if(url_param('id') == false): ?>
<a href="#" class="mw_btn_s new_album_btn"><span>Add new toy</span></a> <br />
<? else : ?>
<a href="#" class="mw_btn_s new_album_btn"><span>Add more pictures to gallery</span></a> <br />
<? endif; ?>
<br />

 



<form action="" method="post" class="upload_pictures_form form hidden" id="pic_upload1" enctype="multipart/form-data">
  <input name="taxonomy_categories[]" type="hidden" value="toy swap" />
   <? if(url_param('id') != false): ?>
   <input name="id" type="hidden" value="<? print url_param('id'); ?>" />
   <? endif; ?>
  <label><strong>Album title</strong></label>
  <span>
  <input style="width:550px;" class="required" name="title" type="text" value="<? print $the_post['title']; ?>" />
  </span>
  <div class="c" style="padding-bottom: 12px;">&nbsp;</div>
  <div class="ghr">&nbsp;</div>
  <script type="text/javascript">
	$(document).ready(function(){
		$("#more_images").click(function(){
			var up_length = $(".input_Up").length;
            if(up_length<=50){
    			var first_up = $("#more_f input:first");
    			$("#more_f").append("<br><br><input class='input_Up' name='picture_' type='file'>");
    			$("#more_f input:last").attr("name", "picture_" + up_length);
            }
		});
	});
</script>
  <label><strong>Upload Pictures</strong></label>
  <a href="#" id="more_images" class="right">Add more pictures</a>
  <div id="more_f" style="padding-bottom:10px">
    <input class="input_Up" name="picture_0" type="file">
  </div>
  <input name="" type="submit" class="xhidden" />
  <div class="c">&nbsp;</div>
  <br />
  <a href="#" class="btn submit right">Upload</a>
</form>
