<?  if($user_id == false){

	$user_id = user_id();
}


?>
<? $form_values = get_user($user_id); ?>
<?php dbg(__FILE__); ?>
<script type="text/javascript">/*<![CDATA[*/
	$(document).ready(function(){



	});/*]]>*/




$(document).ready(function(){

        var user = document.getElementById('profile-username').value;
        var email = document.getElementById('profile-email').value;
        var firstname = document.getElementById('profile-firstname').value;
        var lastname = document.getElementById('profile-lastname').value;


        if(user=='' || email=='' || firstname=='' || lastname==''){
         //   $("#edit-profile-form input").not("#profile-username, #profile-email, #profile-firstname, #profile-lastname");
           // $("#edit-profile-form input").not("#profile-username, #profile-email, #profile-firstname, #profile-lastname").parents(".item").hidden();
        //    $("#edit-profile-form textarea").disable();
        //    $("#edit-profile-form textarea").parents(".item").hidden();
            //$(".mceEditor").mceDisable();
            $("#first-fields-error").visible();
            $(".stabs-nav li:gt(0)").hidden();
        }





<?php /*
    $("#edit-profile-form input").bind('keyup change', function(){
        var user = document.getElementById('profile-username').value;
        var email = document.getElementById('profile-email').value;
        var firstname = document.getElementById('profile-firstname').value;
        var lastname = document.getElementById('profile-lastname').value;


        if(user=='' || email=='' || firstname=='' || lastname==''){
            $("#edit-profile-form input").not("#profile-username, #profile-email, #profile-firstname, #profile-lastname").disable();
            $("#edit-profile-form input").not("#profile-username, #profile-email, #profile-firstname, #profile-lastname").parents(".item").hidden();
            $("#edit-profile-form textarea").disable();
            $("#edit-profile-form textarea").parents(".item").hidden();
            $(".mceEditor").mceDisable();
            $("#first-fields-error").visible();
            $(".stabs-nav li:gt(0)").hidden();
        }
        else{
           $("#edit-profile-form input").enable();
           $("#edit-profile-form input").parents(".item").visible();
           $("#edit-profile-form textarea").enable();
           $("#edit-profile-form textarea").parents(".item").visible();
           $(".stabs-nav li:gt(0)").visible();
           $(".mceEditor").mceEnable();
           $("#first-fields-error").hidden()
        }

    });
*/ ?>


});


$(document).ready(function(){
	 //refresh_user_picture_info();
	 //jcrop_init();
});

function jcrop_init(){
  var cropWidth = $(cropimg).width();
  var cropHeight = $(cropimg).height();
  var cropMin
  if(cropHeight>cropWidth){
    cropMin = cropWidth;
  }
  else{cropMin=cropHeight}
  if(cropMin>300){
    cropMin=300;
  }
	jcrop_api = $('#the-user-pic-for-crop').Jcrop({
			aspectRatio: 1,
			minSize: new Array(cropMin, cropMin),
			onChange: crop_user_picture_showCoords,
			onSelect: crop_user_picture_showCoords,
            setSelect:new Array(cropMin, cropMin, 0, 0),
            useImg:$('#user_image').attr('src')
	});

}

	 function open_crop_user_picture_info(){

		 refresh_user_picture_info(function(){

             $('#crop-user-picture-div').show();
     $("#jcrop-container").css({
          top:$(window).scrollTop() + 50,
          display:'block'
         });
        mw.box.overlay("#000000");
        jcrop_init();
        $("#jcrop-container").draggable({ handle: ".mwbox-top", containment: 'body'});

	 });






		 //mw.box.element({element:'#crop-user-picture-div'});
    }

	function crop_image_ajax_submit(){

	 var crop_options = {
	 	url:       '<?php print site_url('api/media/crop_picture_by_id') ?>' ,        // override for form's 'action' attribute
		type:      'post'
    };
        $('#crop_form').ajaxSubmit(crop_options);
		$('#crop-user-picture-div').animate({opacity:'0.5'});
		refresh_user_picture_info();

		$("#edit-profile-form").submit();

        $(".navian-blue").fadeOut();

         return false;

	}


	function crop_user_picture_showCoords(c)
			{
				$('#x').val(c.x);
				$('#y').val(c.y);
				$('#x2').val(c.x2);
				$('#y2').val(c.y2);
				$('#w').val(c.w);
				$('#h').val(c.h);
				coords = c;
				if (parseInt(coords.w) > 0)
				{
					var rx = 100 / coords.w;
					var ry = 100 / coords.h;


				}

			};

    function refresh_user_picture_info(callback){


		$.ajax({
		  url: '<?php print site_url('api/media/user_get_picture_info') ?>/rand:'+Math.random(),
		  dataType: 'json',
		 // data: myData,
		  success: function(data) {
    cropimg = new Image();
    cropimg.className = 'abshidden';
    cropimg.onload = function(){  //pod ie dava greshni razmeri
  			$('#the-user-pic-for-crop').attr('src',  data.urls.original+'?rand'+Math.random());

  			$('.user-image-triger').attr('src',  data.urls.original+'?rand'+Math.random());

  			$('#user_picture_media_id').val(data.id);

  			$('#user_image').attr('src', data.urls.original+'?rand'+Math.random() );
            if(typeof callback =='function'){
                   callback.call(this);
            }

    }
    cropimg.src = data.urls.original;
 //   document.body.appendChild(cropimg);
		  }
		});




	}





	function change_pass_show(){

	$('#change_pass_holder input').val("");
	$('#change_pass_holder').fadeIn();


$('.change_pass_holder input').val("");
	$('.change_pass_holder').fadeIn();
	}

	function save_user(){


//var data = $('#edit-profile-form').dataCollect();
var data =  $('#edit-profile-form').serialize(); // serialize the form's data
$.post("<? print site_url('api/user/save') ?>", data, function(resp){

var resp_msg = '';

if(isobj(resp.error) != false){
jQuery.each(resp.error, function(i, val) {

	  resp_msg = resp_msg + '<br />' + val;
    });

mw.box.alert(resp_msg);
	}



if(isobj(resp.success) != false){

  $("#user_save_success").fadeIn();
   $("#edit-profile-form").fadeOut();

<?  if($hide_selector_on_save != false) : ?>

	 setTimeout(function(){


					 $("<? print $hide_selector_on_save ?>").fadeOut();

					 }, 1500);



<? endif; ?>








}





}, "json");





	}



    </script>
<?php $more = CI::model('core')->getCustomFields('table_users', $form_values['id']);

$form_values['custom_fields'] = $more;
?>

<div class="edit-profile-form-holder">
  <?php $compete_profile = CI::model('core')->getParamFromURL ( 'compete_profile' ); ?>
  <?php if($user_edit_done == true) : ?>
  <? /*
  <h1 class="saved">Changes are saved</h1>
 */ ?>
  <?php endif;  ?>
  <?php if($compete_profile == 'yes') : ?>
  <h1 id="profile-please-complete">Please complete your profile</h1>
  <?php endif;  ?>
  <?php if(!empty($user_edit_errors)) : ?>
  <!--<ul class="error">
      <?php foreach($user_edit_errors as $k => $v) :  ?>
      <li><?php print $v ?></li>
      <?php endforeach; ?>
    </ul>-->
  <script type="text/javascript">
        var errors = '<ul class="error"><?php foreach($user_edit_errors as $k => $v) :  ?><li><?php print $v ?></li><?php endforeach; ?></ul>';

        $(document).ready(function(){
            $(window).load(function(){
              mw.box.alert(errors);
            });
        });

    </script>
  <?php endif ?>
  <?php if($user_edit_done == true) : ?>
  <script type="text/javascript">
        var errors = ' <h2>Profile updated!</h2>';

        $(document).ready(function(){
            $(window).load(function(){
              mw.box.alert(errors);
            });


            //$("#edit-profile-form").validate();

        });



    </script>
  <?php endif ?>
  <!-- <form action="<?php print site_url('users/user_action:profile'); ?>" method="post" enctype="multipart/form-data" id="profileForm" class="form validate">-->
  <form action="#" method="post" enctype="multipart/form-data" id="edit-profile-form" class="form validate edit-profile-form ">
    <input type="hidden" name="id" value="<? print $form_values['id']; ?>" />
    <!-- <span id="first-fields-error">You must fill out your: username, first name, last name and email.</span>-->
    
    <? 
	$form_title = option_get('form_title', $params['module_id']);
	if($form_title != '') {
		
		$titl = $form_title;
	} else {
		$titl = "Edit profile for " . user_name($form_values['id']);
	}
	
	?>
    
    <h2 class="edit-profile-form-title"><? print $titl ?></h2>
 
 
      <?
		   $iframe_module_params = array();
		   $iframe_module_params['module'] = 'users/profile_picture_edit';
		     $iframe_module_params['user_id'] = $user_id;
		   $iframe_module_params = base64_encode(serialize($iframe_module_params));



		   ?>
      <div class="stab">
        <div class="item">
          <label>Picture:</label>
          <div class="field">
            <!-- <iframe height="100" width="450"  frameborder="0" scrolling="no" src="<? print site_url('api/module/iframe:'. $iframe_module_params) ?>"></iframe>-->
          </div>
        </div>
        <div class="item">
          <label>Username: *</label>
          <span class="field">
          <input disabled="disabled"   name="username" id="profile-username" class="required"  type="text" value="<?php print $form_values['username'];  ?>" />
          </span> </div>
        <div class="item">
          <label>Email: *</label>
          <span class="field">
          <input name="email" id="profile-email" disabled="disabled"   type="text" value="<?php print $form_values['email'];  ?>" />
          </span> </div>
        <div class="item">
          <label>First name: *</label>
          <span class="field">
          <input name="first_name" id="profile-firstname" type="text"  value="<?php print $form_values['first_name'];  ?>" />
          </span> </div>
        <div class="item">
          <label>Last name: *</label>
          <span class="field">
          <input name="last_name" id="profile-lastname" type="text" value="<?php print $form_values['last_name'];  ?>" />
          </span> </div>
        <? /*
          <div class="item">
          <label>Your paypal address:</label>
          <span class="linput">
          <input  name="custom_field_paypal" type="text" value="<?php print $form_values['custom_fields']['paypal'];  ?>" />
          </span> </div>
          */ ?>
        <div class="item">
          <label>Country:</label>
          <span class="field">
          <input  name="custom_field_country" type="text" value="<?php print $form_values['custom_fields']['country'];  ?>" />
          </span> </div>
        <div class="item">
          <label>City:</label>
          <span class="field">
          <input  name="custom_field_city" type="text" value="<?php print $form_values['custom_fields']['city'];  ?>" />
          </span> </div>
        <div class="item">
          <label>Address:</label>
          <span class="field">
          <input  name="custom_field_address" type="text" value="<?php print $form_values['custom_fields']['address'];  ?>" />
          </span> </div>
        <div class="item">
          <label>Zip:</label>
          <span class="field">
          <input  name="custom_field_zip" type="text" value="<?php print $form_values['custom_fields']['zip'];  ?>" />
          </span> </div>
        <div class="item">
          <label>Phone:</label>
          <span class="field">
          <input  name="custom_field_phone" type="text" value="<?php print $form_values['custom_fields']['phone'];  ?>" />
          </span> </div>
        <div class="item">
          <label>Birth day:</label>
          <span class="field">
          <? /*
<input  name="custom_field_bday" type="text" value="<?php print $form_values['custom_fields']['bday'];  ?>" />
*/ ?>
          <select  name="custom_field_bday" value="<?php print $form_values['custom_fields']['bday'];  ?>" >
            <option value="">Day&nbsp;</option>
            <? for ($i = 1; $i <= 31; $i++) :?>
            <option <? if($form_values['custom_fields']['bday'] == $i): ?> selected="selected" <? endif; ?> value="<? print $i ?>"><? print $i ?>&nbsp;</option>
            <? endfor; ?>
          </select>
          </span> </div>
        <div class="item">
          <label>Birth month:</label>
          <span class="field">
          <select   name="custom_field_bmonth" value="<?php print $form_values['custom_fields']['bmonth'];  ?>" >
            <option value="">Month&nbsp;</option>
            <? for ($i = 1; $i <= 12; $i++) :?>
            <option  <? if($form_values['custom_fields']['bmonth'] == $i): ?> selected="selected" <? endif; ?>   value="<? print $i ?>"><? print  date("F", mktime(0, 0, 0, $i, 1, 2000)); ?>&nbsp;</option>
            <? endfor; ?>
          </select>
          </span> </div>
        <div class="item">
          <label>Birth year:</label>
          <span class="field">
          <select  name="custom_field_byear" value="<?php print $form_values['custom_fields']['byear'];  ?>" >
            <option value="">Year&nbsp;</option>
            <? for ($i = date("Y"); $i >= 1950; $i--) :?>
            <option <? if($form_values['custom_fields']['byear'] == $i): ?> selected="selected" <? endif; ?> value="<? print $i ?>"><? print $i ?>&nbsp;</option>
            <? endfor; ?>
          </select>
          </span> </div>
        <div class="item" style="">
          <label>About me text:</label>
          <span class="area">
          <textarea   name="custom_field_about" type="text" ><?php print $form_values['custom_fields']['about'];  ?></textarea>
          </span> </div>
        <? // p($form_values); ?>
        <!--   <h4>Your account will expire on <? print $form_values['expires_on'] ?></h4>-->
        <?php /*
        <input name="test" type="button" onClick="refresh_user_picture_info()" value="test" />
        <input name="test2" type="button" onClick="open_crop_user_picture_info()" value="test2" />
*/ ?>
        <div class="item">
          <label>Save profile:</label>
          <span class="field"> <a href="javascript:if($('#edit-profile-form').isValid()){save_user()};" class="dit-profile-form-save save-user-btn">Save</a> </span> </div>
       
    <div class="item">
      <label><a href='javascript:change_pass_show();' class="change-pass-btn">change password</a> </label>
      <div class="change_pass_holder field" style="display:none">
        <table width="100%" border="0">
          <tr>
            <td><label>Enter new password:</label>
              <span class="field">
              <input class="required-equal" equalto="pass" type="password" name="password"   value="<?php print $form_values['password'];  ?>" />
              </span></td>
          </tr>
          <tr>
            <td><label>Repeat new password:</label>
              <span class="field">
              <input class="required-equal" equalto="pass" type="password" value="<?php print $form_values['password'];  ?>"  />
              </span></td>
          </tr>
        </table>
      </div>
    </div>
    <!--<a href="javascript:;" class="btn submit">SAVE</a>-->
  </form>
  <div id="user_save_success" style="display:none;">
    <h2>Profile saved.</h2>
  </div>
  <?php //require (ACTIVE_TEMPLATE_DIR.'users/right_sidebar.php') ?>
</div>
<?php dbg(__FILE__, 1); ?>
