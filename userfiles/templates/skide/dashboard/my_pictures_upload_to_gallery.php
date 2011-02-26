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
        html:$('#pic_upload_to_gallery')
    });
    $(new_album_pop).css({
      height:'auto'
    });

        modal.overlay();

    });


});










$(document).ready(function() { 
    var options = {
       url:       "<? print site_url('api/media/user_upload_picture'); ?>",
	   type: 'post',

	   //target:        '#output2',   // target element(s) to be updated with server response
        beforeSubmit:  showRequest,  // pre-submit callback 
		dataType:  'json' ,       // 'xml', 'script', or 'json' (expected server response type) 
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
    $('#pic_upload_to_gallery').submit(function() {
        // inside event callbacks 'this' is the DOM element so we first 
        // wrap it in a jQuery object and then invoke ajaxSubmit
        $("#pic_upload_to_gallery input[type='file']").is(":hasnoval").remove();
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
    return $("#pic_upload_to_gallery").isValid();;
} 

// post-submit callback 
function showResponse(responseText, statusText, xhr, $form)  { 
    // for normal html responses, the first argument to the success callback 
    // is the XMLHttpRequest object's responseText property
 // modal.close();
  if(responseText.id){
	  //window.location.href = '';
	  //alert('Pic uploaded, Alex must refresh to new the pic '+responseText.id);
      var last_id = 0;
      if($(".gallery_item").length>0){
        var last_id = $(".gallery_item:last").attr("id");
        var last_id = parseFloat(last_id.replace("gimage_", ""))+$(".input_Up:hasval").length;
      }
      else{
        var last_id = $(".input_Up:hasval").length-1;
      }



      window.location.href = "#gimage_" + last_id;
      window.location.reload();
  }
  //alert(responseText.id);
  //window.location.reload(true);
    // if the ajaxSubmit method was passed an Options Object with the dataType
    // property set to 'xml' then the first argument to the success callback 
    // is the XMLHttpRequest object's responseXML property
 
    // if the ajaxSubmit method was passed an Options Object with the dataType 
    // property set to 'json' then the first argument to the success callback
    // is the json data object returned by the server 

   // alert('status: ' + statusText + '\n\nresponseText: \n' + responseText +    '\n\nThe output div should have already been updated with the responseText.'); 
}


function show_gallery_edit(){
	
	var new_album_pop = modal.init({
        width:600,
        height:'auto',
        customPosition:{
          left:'center',
          top:$(window).scrollTop()+70
        },
        html:$('#edit_gallery_holder')
    });
    $(new_album_pop).css({
      height:'auto'
    });

        modal.overlay();
		$('#edit_gallery_holder').fadeIn();


}

function delete_gallery(id){
	mw.content.del(id, function(){
	    window.location.href = "<? print site_url('dashboard/action:my-pictures'); ?>" ;
	});
	
}




</script>
<? $the_post = get_post(url_param('id')); ?>
 <? if(url_param('id') == false): ?>
<a href="#" class="mw_btn_s new_album_btn"><span>Create new album </span></a><br />
<? else : ?>
<? /*
<a href="#" class="mw_btn_s new_album_btn"><span>All pictures</span></a> <br />
*/ ?>

<a href="<? print site_url('dashboard/action:my-pictures'); ?>" class="btn left"><span><big>&larr;&nbsp;</big>Back to albums</span></a>

<a href="javascript: show_gallery_edit()" class="mw_btn_s right"><span>Edit gallery</span></a> <br />

<div class="c">&nbsp;</div>
<a href="javascript: delete_gallery(<? print url_param('id') ?>)" class="red"><u>Delete gallery </u></a> <br />
<? endif; ?>
<br />





<div  id="edit_gallery_holder">
<form action="" method="post" class="upload_pictures_form form" id="pic_upload_to_gallery" enctype="multipart/form-data">
 
   <? if(url_param('id') != false): ?>
   <input name="id" type="hidden" value="<? print url_param('id'); ?>" />
   <? endif; ?>

 
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
</div>