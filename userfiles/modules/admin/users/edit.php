
<div class="box radius">
  <div class="box_header radius_t">
    <h2>Edit user profile</h2>
  </div>
  <div class="box_content">
    <?  
$user_id = $params['user_id'];
$user_id =  intval($user_id );
if($user_id == false){
	
	//$user_id = user_id();
}


?>
    <? 

if($user_id != 0){
$form_values = get_user($user_id); 
}

?>
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
         //   $("#profileForm input").not("#profile-username, #profile-email, #profile-firstname, #profile-lastname");
           // $("#profileForm input").not("#profile-username, #profile-email, #profile-firstname, #profile-lastname").parents(".item").hidden();
        //    $("#profileForm textarea").disable();
        //    $("#profileForm textarea").parents(".item").hidden();
            //$(".mceEditor").mceDisable();
            $("#first-fields-error").visible();
            $(".stabs-nav li:gt(0)").hidden();
        }





<?php /*
    $("#profileForm input").bind('keyup change', function(){
        var user = document.getElementById('profile-username').value;
        var email = document.getElementById('profile-email').value;
        var firstname = document.getElementById('profile-firstname').value;
        var lastname = document.getElementById('profile-lastname').value;


        if(user=='' || email=='' || firstname=='' || lastname==''){
            $("#profileForm input").not("#profile-username, #profile-email, #profile-firstname, #profile-lastname").disable();
            $("#profileForm input").not("#profile-username, #profile-email, #profile-firstname, #profile-lastname").parents(".item").hidden();
            $("#profileForm textarea").disable();
            $("#profileForm textarea").parents(".item").hidden();
            $(".mceEditor").mceDisable();
            $("#first-fields-error").visible();
            $(".stabs-nav li:gt(0)").hidden();
        }
        else{
           $("#profileForm input").enable();
           $("#profileForm input").parents(".item").visible();
           $("#profileForm textarea").enable();
           $("#profileForm textarea").parents(".item").visible();
           $(".stabs-nav li:gt(0)").visible();
           $(".mceEditor").mceEnable();
           $("#first-fields-error").hidden()
        }

    });
*/ ?>


});


$(document).ready(function(){
	 refresh_user_picture_info();
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

		$("#profileForm").submit();

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
  //  document.body.appendChild(cropimg);
		  }
		});




	}
	
	
	
	
	
	function change_pass_show(){
	
	$('#change_pass_holder input').val("");
	$('#change_pass_holder').fadeIn();

	}
	
	function save_user(){
	 

//var data = $('#profileForm').dataCollect();
var data =  $('#profileForm').serialize(); // serialize the form's data
$.post("<? print site_url('api/user/save') ?>", data, function(resp){

var resp_msg = '';

if(isobj(resp.error) != false){
jQuery.each(resp.error, function(i, val) {
    
	  resp_msg = resp_msg + '<br />' + val;
    });	

//mw.box.alert(resp_msg);
	}



if(isobj(resp.success) != false){

  $("#user_save_success").fadeIn();
   $("#profileForm").fadeOut();

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
    <div>
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


            //$("#profileForm").validate();

        });



    </script>
      <?php endif ?>
      <!-- <form action="<?php print site_url('users/user_action:profile'); ?>" method="post" enctype="multipart/form-data" id="profileForm" class="form validate">-->
      <form action="#" method="post" enctype="multipart/form-data" id="profileForm" class="form validate edit_profile_form">
        <input type="hidden" name="id" value="<? print $form_values['id']; ?>" />
        <!-- <span id="first-fields-error">You must fill out your: username, first name, last name and email.</span>-->
        <br />
        <div class="bluebox">
          <div class="blueboxcontent">
            <div class="hr"></div>
            <div class="ghr"></div>
            <div class="stabs" style="padding-top: 15px;">
              <ul class="stabs-nav">
                <!--  <li><a href="#">Change password</a></li>-->
              </ul>
              <? 
		//   $iframe_module_params = array();
//		   $iframe_module_params['module'] = 'users/profile_picture_edit';
//		     $iframe_module_params['user_id'] = $user_id;
//		   $iframe_module_params = base64_encode(serialize($iframe_module_params));

		   

		   ?>
            <!--  <iframe height="100" width="450"  frameborder="0" scrolling="no" src="<? print site_url('api/module/iframe:'. $iframe_module_params) ?>"></iframe>-->
              <div class="stab">
                <div class="formitem">
                  <label>Username: *</label>
                  <span class="formfield">
                  <input style="color: #999;" name="username" id="profile-username" class="required"  type="text" value="<?php print $form_values['username'];  ?>" />
                  </span> </div>
                <div class="formitem">
                  <label>Email: *</label>
                  <span class="formfield">
                  <input name="email" id="profile-email"  type="text" value="<?php print $form_values['email'];  ?>" />
                  </span> </div>
                <div class="c">&nbsp;</div>
                <div class="formitem">
                  <label>First name: *</label>
                  <span class="formfield">
                  <input name="first_name" id="profile-firstname" type="text"  value="<?php print $form_values['first_name'];  ?>" />
                  </span> </div>
                <div class="formitem">
                  <label>Last name: *</label>
                  <span class="formfield">
                  <input name="last_name" id="profile-lastname" type="text" value="<?php print $form_values['last_name'];  ?>" />
                  </span> </div>
                <div class="c">&nbsp;</div>
                <? /*
          <div class="formitem">
          <label>Your paypal address:</label>
          <span class="linput">
          <input  name="custom_field_paypal" type="text" value="<?php print $form_values['custom_fields']['paypal'];  ?>" />
          </span> </div>
          */ ?>
                <div class="formitem">
                  <label>Country:</label>
                  <span class="formfield">
                  <input  name="custom_field_country" type="text" value="<?php print $form_values['custom_fields']['country'];  ?>" />
                  </span> </div>
                <div class="formitem">
                  <label>City:</label>
                  <span class="formfield">
                  <input  name="custom_field_city" type="text" value="<?php print $form_values['custom_fields']['city'];  ?>" />
                  </span> </div>
                <div class="c">&nbsp;</div>
                <div class="formitem">
                  <label>Birth day:</label>
                  <span class="formfield">
                  <? /*
<input  name="custom_field_bday" type="text" value="<?php print $form_values['custom_fields']['bday'];  ?>" />
*/ ?>
                  <select style="width: 225px;" name="custom_field_bday" value="<?php print $form_values['custom_fields']['bday'];  ?>" >
                    <option value="">Day&nbsp;</option>
                    <? for ($i = 1; $i <= 31; $i++) :?>
                    <option <? if($form_values['custom_fields']['bday'] == $i): ?> selected="selected" <? endif; ?> value="<? print $i ?>"><? print $i ?>&nbsp;</option>
                    <? endfor; ?>
                  </select>
                  </span> </div>
                <div class="formitem">
                  <label>Birth month:</label>
                  <span class="formfield">
                  <select style="width: 225px;"  name="custom_field_bmonth" value="<?php print $form_values['custom_fields']['bmonth'];  ?>" >
                    <option value="">Month&nbsp;</option>
                    <? for ($i = 1; $i <= 12; $i++) :?>
                    <option  <? if($form_values['custom_fields']['bmonth'] == $i): ?> selected="selected" <? endif; ?>   value="<? print $i ?>"><? print  date("F", mktime(0, 0, 0, $i, 1, 2000)); ?>&nbsp;</option>
                    <? endfor; ?>
                  </select>
                  </span> </div>
                <div class="formitem">
                  <label>Birth year:</label>
                  <span class="formfield">
                  <select style="width: 225px;" name="custom_field_byear" value="<?php print $form_values['custom_fields']['byear'];  ?>" >
                    <option value="">Year&nbsp;</option>
                    <? for ($i = date("Y"); $i >= 1950; $i--) :?>
                    <option <? if($form_values['custom_fields']['byear'] == $i): ?> selected="selected" <? endif; ?> value="<? print $i ?>"><? print $i ?>&nbsp;</option>
                    <? endfor; ?>
                  </select>
                  </span> </div>
                <div class="c">&nbsp;</div>
                <div class="formitem" style="">
                  <label>About me:</label>
                  <span class="formfield">
                  <textarea style="width: 420px;"  name="custom_field_about" type="text" ><?php print $form_values['custom_fields']['about'];  ?></textarea>
                  </span> </div>
                <div class='c'>&nbsp;</div>
                <? // p($form_values); ?>
                <div class="c" style="padding-bottom: 15px;">&nbsp;</div>
                <h4>Your account will expire on <? print $form_values['expires_on'] ?></h4>
                <div class="c">&nbsp;</div>
                <?php /*
        <input name="test" type="button" onClick="refresh_user_picture_info()" value="test" />
        <input name="test2" type="button" onClick="open_crop_user_picture_info()" value="test2" />
*/ ?>
                <div class='c'>&nbsp;</div>
                <br />
                <div class="formitem"> <span>Change password: </span> <a href='javascript:change_pass_show();' class="btn_small">click here</a>
                  <div class='c' style="padding-bottom: 10px;">&nbsp;</div>
                  <div id="change_pass_holder" style="display:none">
                    <label>Enter new password</label>
                    <span>
                    <input class="required-equal" equalto="pass" type="password" name="password"  value="<?php print $form_values['password'];  ?>" />
                    </span>
                    <label>Repeat new password</label>
                    <span>
                    <input class="required-equal" equalto="pass" type="password" value="<?php print $form_values['password'];  ?>"  />
                    </span> </div>
                </div>
                <div class='c'>&nbsp;</div>
                <br />
                <br />
                <div class='c'>&nbsp;</div>
              </div>
            </div>
          </div>
        </div>
        <br />
        <div align="left"> <a href="javascript:save_user()" class="btn"><span>Save changes</span></a> </div>
        <br />
        <br />
        <!--<a href="javascript:;" class="btn submit">SAVE</a>-->
      </form>
      <div id="user_save_success" style="display:none;">
        <h2>Profile saved.</h2>
      </div>
      <?php //require (ACTIVE_TEMPLATE_DIR.'users/right_sidebar.php') ?>
    </div>
    <?php dbg(__FILE__, 1); ?>
  </div>
</div>
