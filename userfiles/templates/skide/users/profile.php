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
    document.body.appendChild(cropimg);
		  }
		});


 

	}

	
    </script>
<?php $more = CI::model('core')->getCustomFields('table_users', $form_values['id']); 
$form_values['custom_fields'] = $more;
?>


<div class="wrap">
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
  <form action="<?php print site_url('users/user_action:profile'); ?>" method="post" enctype="multipart/form-data" id="profileForm" class="form validate">
    <? /*
    <h2 class="title inline left">Profile Settings</h2>
    */ ?>
    <!-- <span id="first-fields-error">You must fill out your: username, first name, last name and email.</span>-->


<br />


<div class="bluebox">
<div class="blueboxcontent">

 <h2 style="padding-bottom: 5px">Personal Information </h2>
 <div class="hr"></div>
 <div class="ghr"></div>


    <div class="stabs" style="padding-top: 15px;">
      <ul class="stabs-nav">
        <!--  <li><a href="#">Change password</a></li>-->
      </ul>

      <a name="edit-profile-image" id="edit-profile-image"></a>
        <div name="user_image" id="user_image_holder">

          <?php $thumb = CI::model('users')->getUserThumbnail( $user_id, 250); ?>
          <?php if($thumb != ''): ?>
          <img id='user_image' class="the-user-pic"  src="<?php print $thumb; ?>" /> <br />
                  <input class="cinput input_Up" id="profile_picture" name="picture_0" style="height:auto" type="file" />
                    
                    
                  <? /*
                      <input class="cinput input_Up" id="profile_picture" name="picture_1" style="height:auto" type="file" />
                  */ ?>
                    <br />
          <a id='user_image_href' href="javascript:userPictureDelete('<?php echo $user_id ?>')">Delete photo</a>
          <? else: ?>
          Upload a picture<br />

		   <input class="cinput input_Up" id="profile_picture" name="picture_0" style="height:auto" type="file" />
		  <?php endif; ?>
            
        </div>

      <div class="stab">

        <div class="item">
          <label>Username: *</label>
          <span class="field">
          <input name="username" id="profile-username" class="required"  type="text" value="<?php print $form_values['username'];  ?>" />
          </span> </div>
        <div class="item">
          <label>Email: *</label>
          <span class="field">
          <input name="email" id="profile-email"  type="text" class="required-email" value="<?php print $form_values['email'];  ?>" />
          </span> </div>

     <div class="c">&nbsp;</div>
        <div class="item">
          <label>First name: *</label>
          <span class="field">
          <input name="first_name" id="profile-firstname" type="text" class="required" value="<?php print $form_values['first_name'];  ?>" />
          </span> </div>


        <div class="item">
          <label>Last name: *</label>
          <span class="field">
          <input class="required" name="last_name" id="profile-lastname" type="text" value="<?php print $form_values['last_name'];  ?>" />
          </span> </div>
          
     <div class="c">&nbsp;</div>
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
           <div class="c">&nbsp;</div>
           <div class="item">
          <label>Birth day:</label>
          <span class="field">
          <? /*
<input  name="custom_field_bday" type="text" value="<?php print $form_values['custom_fields']['bday'];  ?>" />
*/ ?>
          <select style="width: 225px;" name="custom_field_bday" value="<?php print $form_values['custom_fields']['bday'];  ?>" >
            <option value="">Day&nbsp;</option>
            <? for ($i = 1; $i <= 31; $i++) :?>
            <option <? if($form_values['custom_field_bday'] == $i): ?> selected="selected" <? endif; ?> value="<? print $i ?>"><? print $i ?>&nbsp;</option>
            <? endfor; ?>
          </select>

          </span> </div>
          
            <div class="item">
          <label>Birth month:</label>
          <span class="field">
          <select style="width: 225px;"  name="custom_field_bmonth" value="<?php print $form_values['custom_fields']['bmonth'];  ?>" >

          <option value="">Month&nbsp;</option>
            <? for ($i = 1; $i <= 12; $i++) :?>
            <option  <? if($form_values['custom_field_bmonth'] == $i): ?> selected="selected" <? endif; ?>   value="<? print $i ?>"><? print  date("F", mktime(0, 0, 0, $i, 1, 2000)); ?>&nbsp;</option>
            <? endfor; ?>
           </select>

          </span> </div>
          
          
              <div class="item">
          <label>Birth year:</label>
          <span class="field">
          <select style="width: 225px;" name="custom_field_byear" value="<?php print $form_values['custom_fields']['byear'];  ?>" >

              <option value="">Year&nbsp;</option>
            <? for ($i = date("Y"); $i >= 1950; $i--) :?>
            <option <? if($form_values['custom_field_byear'] == $i): ?> selected="selected" <? endif; ?> value="<? print $i ?>"><? print $i ?>&nbsp;</option>
            <? endfor; ?>

          </select>
          </span> </div>
         <div class="c">&nbsp;</div>
          
          <div class="item" style="">
          <label>About me:</label>
          <span class="field">
          <textarea style="width: 420px;"  name="custom_field_about" type="text" value="<?php print $form_values['custom_fields']['about'];  ?>" ></textarea>
          </span> </div>

          
        <div class='c'>&nbsp;</div>
        <div class="c">&nbsp;</div>
        <?php /*
        <input name="test" type="button" onClick="refresh_user_picture_info()" value="test" />
        <input name="test2" type="button" onClick="open_crop_user_picture_info()" value="test2" />
*/ ?>

        <div class='c'>&nbsp;</div>
<br />
        <div class="item">
          <span>Change password: </span>
          <a href="javascript:mw.users.ChangePass()" class="mw_blue_link">click here</a> </div>
        <div class='c'>&nbsp;</div>
        <br />
        <br />
        <div class='c'>&nbsp;</div>
      </div>
    </div>

    </div>
</div>
<br />
<div align="center">
    <a href="#" class="mw_btn_x submit"><span>Save changes</span></a>
</div>
    <br /><br />
    <!--<a href="javascript:;" class="btn submit">SAVE</a>-->
  </form>
  <?php //require (ACTIVE_TEMPLATE_DIR.'users/right_sidebar.php') ?>
</div>
<?php dbg(__FILE__, 1); ?>
