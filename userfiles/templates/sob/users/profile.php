<?php dbg(__FILE__); ?>















<script type="text/javascript">/*<![CDATA[*/
	$(document).ready(function(){
		/* Example 1 */
		var button = $('#profile_picture');

		new AjaxUpload(button, {
			action: '<?php print site_url('api/media/user_upload_picture') ?>', 
			name: 'picture_0',
			autoSubmit: true,
			onSubmit : function(file, ext){

			  if (! (ext && /^(jpg|png|jpeg|gif)$/i.test(ext))){
                        // extension is not allowed
                        mw.box.alert('Error: invalid file extension. <br /><br />Please upload an image file: .jpg or .gif or .png');
                        // cancel upload
                        return false;
                }
                mw.box.overlay('#000000');

				// change button text, when user selects file
				//button.text('Uploading');

				// If you want to allow uploading only 1 file at time,
				// you can disable upload button
				this.disable();



			},
			onComplete: function(file, response){



				// enable upload button
				this.enable();
				if(response == 'ok'){
					open_crop_user_picture_info();
				} else {
					 mw.box.alert('There was an error uploading your picture.');
				}

				

				// add file to the list
				//$('<li></li>').appendTo('#example1 .files').text(file);
			}
		});
	
		 
	});/*]]>*/
	



$(document).ready(function(){

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







<div class="mwbox-container mw-modal" id="jcrop-container" style="top: 0;">
   <div class="mwbox-top">
      <div class="mwbox-topleft">&nbsp;</div>

      <div class="mwbox-topright">&nbsp;</div>
      <div class="mwbox-topmid">&nbsp;</div>
   </div>
   <div class="mwbox-left">
      <div class="mwbox-right">
          <div class="mwbox-main-content" style="width:400px">
              <div class="mwbox-content">


<div id="crop-user-picture-div">



    <img id='the-user-pic-for-crop'  src="" />
    
    

    
    
    <form id="crop_form">
    <input type="text"  id="user_picture_media_id" name="id" />
    
			<label>X1 <input type="text" size="4" id="x" name="x" /></label>
			<label>Y1 <input type="text" size="4" id="y" name="y" /></label>
			<label>X2 <input type="text" size="4" id="x2" name="x2" /></label>
			<label>Y2 <input type="text" size="4" id="y2" name="y2" /></label>
			<label>W <input type="text" size="4" id="w" name="w" /></label>
			<label>H <input type="text" size="4" id="h" name="h" /></label>
            <input name="save" type="button" onClick="crop_image_ajax_submit()" value="save" />
		</form>




</div>

              </div>

          </div>
      </div>
   </div>
   <div class="mwbox-bottom">
      <div class="mwbox-bottomleft">&nbsp;</div>
      <div class="mwbox-bottomright">&nbsp;</div>
      <div class="mwbox-bottommid">&nbsp;</div>
   </div>
   <a href="#" class="navian-blue title-tip" title="Crop image" onclick="crop_image_ajax_submit()"><strong>CROP</strong></a>
</div>






<?php require (ACTIVE_TEMPLATE_DIR.'dashboard/dashboard_sidebar.php') ?>
<?php $more = $this->core_model->getCustomFields('table_users', $form_values['id']); ?>

<div id="profile-main">
  <?php $compete_profile = $this->core_model->getParamFromURL ( 'compete_profile' ); ?>
  <?php if($user_edit_done == true) : ?>
  <h1 class="saved">Changes are saved</h1>
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
  <form action="<?php print site_url('users/user_action:profile'); ?>" method="post" enctype="multipart/form-data" id="profileForm" class="validate">
  <h2 class="title inline left">Profile Settings</h2>
 <!-- <span id="first-fields-error">You must fill out your: username, first name, last name and email.</span>-->
  
  
  <a href="#help-profile" class="master-help right">What is this?</a>
  <div class="c"></div>
  <div class="master-help" id="help-profile">
  In this section you can edit your personal information, upload a new profile picture,
  embed or upload a welcome video, share or change your "On the Web" information, such as Skype address,
  ICQ, Twitter account, etc. and fill in information about yourself in the "About Me" section.
  </div>
  <div class="c" style="padding-bottom: 10px;"></div>
    <div class="stabs">


      <ul class="stabs-nav">
        <li><a href="#">Personal Information</a></li>
        <li><a href="#">Welcome video</a></li>
        <li><a href="#">On the web</a></li>
        <li><a href="#">About Me</a></li>
        <!--  <li><a href="#">Change password</a></li>-->
      </ul>
      
      <div class="stab">

        <h2><a href="#help-Personal-Information" class="master-help right" style="margin: 0">What is this?</a> Personal Information

        <div class="master-help" id="help-Personal-Information">
            Fill in your personal information. You can add more than one personal or company
            website by hitting the PLUS sign on the right side of the "Website" box.
            Please note that you must click on "Save changes" button every time you wish to change some information about yourself.
        </div>
        </h2>






        <div class="item">
          <label>Username: *</label>
          <span class="linput">
          <input name="username" id="profile-username" class="required"  type="text" value="<?php print $form_values['username'];  ?>" />
          </span> </div>
        <div class="item">
          <label>Email: *</label>
          <span class="linput">
          <input name="email" id="profile-email"  type="text" class="required-email" value="<?php print $form_values['email'];  ?>" />
          </span> </div>
        <div class='c'>&nbsp;</div>
        <div class="item">
          <label>First name: *</label>
          <span class="linput">
          <input name="first_name" id="profile-firstname" type="text" class="required" value="<?php print $form_values['first_name'];  ?>" />
          </span> </div>
        <div class='c'>&nbsp;</div>
        <div class="item">
          <label>Last name: *</label>
          <span class="linput">
          <input class="required" name="last_name" id="profile-lastname" type="text" value="<?php print $form_values['last_name'];  ?>" />
          </span> </div>
        <div class='c'>&nbsp;</div>
        <div class="item MyWebSite">
          <label>Website: </label>
          <span class="linput">
          <input name="user_website" type="text" value="<?php print $form_values['user_website'];  ?>" />
          <span id="moresites" title="Add more sites" class="title-tip" onclick="addMoreSites()">&nbsp;</span> </span> </div>
        <div class="item">
          <label>Blog: </label>
          <span class="linput">
          <input name="user_blog" type="text" value="<?php print $form_values['user_blog'];  ?>" />
          </span> </div>
        <div id="more-websites">
          <?php if(!empty($more)): ?>
          <?php foreach($more as $k => $v): ?>
          <?php if(trim($v) != '')  : ?>
          <?php if(stristr($k, 'website_') != FALSE)  : ?>
          <div class="item MyWebSite">
            <label>Website: </label>
            <span class="linput">
            <input name="custom_field_ <?php print($k); ?>" type="text" value="<?php print (trim($v)); ?>" />
            </span> </div>
          <?php endif; ?>
          <?php endif; ?>
          <?php endforeach; ?>
          <?php endif; ?>
        </div>
        <div class="c">&nbsp;</div>
        
<?php /*
        <input name="test" type="button" onClick="refresh_user_picture_info()" value="test" />
        <input name="test2" type="button" onClick="open_crop_user_picture_info()" value="test2" />
*/ ?>
        
        <a name="edit-profile-image" id="edit-profile-image"></a>
        <div class="item" name="user_image" id="user_image_holder" style="width: 400px;">
          <label> Picture/Logo: </label>
          <input class="cinput input_Up" id="profile_picture" name="picture_0" style="height:auto" type="file" />
          <?php $thumb = $this->users_model->getUserThumbnail( $form_values['id'], 250); ?>
          <?php if($thumb != ''): ?>
          <img id='user_image' class="the-user-pic"  src="<?php print $thumb; ?>" /> <br />
          <a id='user_image_href' href="javascript:userPictureDelete('<?php echo $form_values['id']?>')">Delete photo</a>
          <?php endif; ?>
        </div>
        <div class='c'>&nbsp;</div>
        <div class="item">
          <label>Country:</label>
          <span class="linput">
          <input name="country" type="text" value="<?php print $form_values['country'];  ?>" />
          </span> </div>
        <div class="item">
          <label>State:</label>
          <span class="linput">
          <input name="state" type="text" value="<?php print $form_values['state'];  ?>" />
          </span> </div>
        <div class="item">
          <label>Address: </label>
          <span class="larea">
          <textarea name="addr1" cols="" rows=""><?php print $form_values['addr1'];  ?></textarea>
          </span> </div>
        <div class='c'>&nbsp;</div>
        <div class="item">
          <label>City: </label>
          <span class="linput">
          <input name="city" type="text" value="<?php print $form_values['city'];  ?>" />
          </span> </div>
        <div class="item">
          <label>Zip: </label>
          <span class="linput">
          <input name="zip" type="text" value="<?php print $form_values['zip'];  ?>" />
          </span> </div>
        <div class='c'>&nbsp;</div>
        <div class="item">
          <label>Phone: </label>
          <span class="linput">
          <input name="phone" type="text" value="<?php print $form_values['phone'];  ?>" />
          </span> </div>
           <div class='c'>&nbsp;</div>
           <div class="item">
          <label>Change password: </label>
            <a href="javascript:mw.users.ChangePass()" class="item-link">click here</a>
          </div>
          
          
          
          
        <div class='c'>&nbsp;</div>
        <br />
        <br />
        <div class='c'>&nbsp;</div>
      </div>
      <div class="stab" style="padding-bottom: 15px;">
        <h2>Welcome video
        <a href="#help-Welcome-video" class="master-help right" style="margin: 0">What is this?</a> Personal Information

        <div class="master-help" id="help-Welcome-video">

<p>In this section you can instantly upload or embed a welcome video.
It will appear on your profile right bellow your profile picture and the "About me" information.
This will be the first thing one finds out about you, so be creative!</p>
<p>To upload a video simply click on the Browse button and choose the desired video from your hard disk.
Then click the "Upload" button. Way to go! Your video is uploaded. Please note that you must click on the
"Save changes" button on the bottom of the page every time you wish to confirm the file you've uploaded.</p>

<p>To embed a video from YouTube or any other website, just copy the Embed HTML code and paste it in the "Embed code" box
and your video will appear on the screen. Then hit the "Save changes" button to confirm the action.
Well done! <br />
Your welcome video will now appear on your profile and greet all the visitors of your personal SOB page.</p>

        </div>

        </h2>
        <div class="c">&nbsp;</div>
        <div class="item" style="width: 400px">
          <label>Upload video: </label>
          <input name="video" type="file" />
          
          <!--<label> or Paste YouTube link: </label>
       <input name="video_url" type="text" value="<?php print $form_values['video_url'];  ?>" />-->
          
          <?php $media  = $this->core_model->mediaGetAndCache($to_table = 'table_users', $to_table_id = $form_values['id'], $media_type = 'video', 'DESC');
		//p($media);
		?>
          <?php if(!empty($media['videos'])): ?>
          <object id="player" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" name="player" width="400" height="315">
            <param name="movie" value="<?php print TEMPLATE_URL ?>js/mediaplayer-5.2-viral/player.swf" />
            <param name="allowfullscreen" value="true" />
            <param name="allowscriptaccess" value="always" />
            <param name="flashvars" value="file=<?php print $media['videos'][0]['url']; ?>&image=<?php print TEMPLATE_URL ?>js/mediaplayer-5.2-viral/preview.jpg" />
            <embed
			type="application/x-shockwave-flash"
			id="player2"
			name="player2"
			src="<?php print TEMPLATE_URL; ?>js/mediaplayer-5.2-viral/player.swf"
			width="400" 
			height="315"
			allowscriptaccess="always"
			allowfullscreen="true"
			flashvars="file=<?php print $media['videos'][0]['url']; ?>&image=<?php print TEMPLATE_URL; ?>js/mediaplayer-5.2-viral/preview.jpg" 		/>
          </object>
          <?php else : ?>
          <?php endif; ?>
          <div class="item" style="width: 550px;margin:0 0 0 121px;padding:20px 0 10px;">

            If you don't want to upload video, please paste the embed code here:

          </div>
          <div class="item" style="width: 540px;">
            <label>Embed code: </label>
            <span class="larea">
            <textarea name="video_embed" onkeyup="$('#EmbedPreview').html(this.value);" style="width: 400px;height: 100px"><?php print $form_values['video_embed'];  ?></textarea>
            </span> </div>
        </div>
        <div class="profile-video-object" id="EmbedPreview"> <?php print   html_entity_decode($form_values['video_embed']);  ?> </div>
      </div>
      <div class="stab">
        <h2><a href="#help-on-the-web" class="master-help right" style="margin: 0">What is this?</a>On the web
        <div class="master-help" id="help-on-the-web">
          In this section you share your Instant Messenger and Social Media Network information with everyone.
          Decide what's important for you to share with the SOB crowd and how easily you wish to be contacted
          outside of the SOB network.
          You can freely add your Skype, Gtalk, ICQ, MSN, Facebook, MySpace, LinkedIn, Twitter and YouTube contacts.
        </div>
        </h2>
        <div class="item">
          <label>Skype: </label>
          <span class="linput">
          <input name="chat_skype" type="text" value="<?php print $form_values['chat_skype'];  ?>" />
          </span> </div>
        <div class="item">
          <label>Gtalk: </label>
          <span class="linput">
          <input name="chat_googletalk" type="text" value="<?php print $form_values['chat_googletalk'];  ?>" />
          </span> </div>
        <div class='c'>&nbsp;</div>
        <div class="item">
          <label>ICQ: </label>
          <span class="linput">
          <input name="chat_icq" type="text" value="<?php print $form_values['chat_icq'];  ?>" />
          </span> </div>
        <div class="item">
          <label>MSN: </label>
          <span class="linput">
          <input name="chat_msn" type="text" value="<?php print $form_values['chat_msn'];  ?>" />
          </span> </div>
        <div class='c'>&nbsp;</div>
        <div class="item">
          <label>Facebook: </label>
          <span class="linput">
          <input name="social_facebook" type="text" value="<?php print $form_values['social_facebook'];  ?>" />
          </span> </div>
        <div class="item">
          <label>Myspace: </label>
          <span class="linput">
          <input name="social_myspace" type="text" value="<?php print $form_values['social_myspace'];  ?>" />
          </span> </div>
        <div class='c'>&nbsp;</div>
        <div class="item">
          <label>Linkedin: </label>
          <span class="linput">
          <input name="social_linkedin" type="text" value="<?php print $form_values['social_linkedin'];  ?>" />
          </span> </div>
        <div class="item">
          <label>Twitter: </label>
          <span class="linput">
          <input name="social_twitter" type="text" value="<?php print $form_values['social_twitter'];  ?>" />
          </span> </div>
        <div class='c'>&nbsp;</div>
        <div class="item">
          <label>Youtube: </label>
          <span class="linput">
          <input name="social_youtube" type="text" value="<?php print $form_values['social_youtube'];  ?>" />
          </span> </div>
      </div>
      <div class="stab" style="padding-bottom: 15px;">
        <h2><a href="#help-about-me" class="master-help right" style="margin: 0">What is this?</a>About Me

        <div class="master-help" id="help-about-me">
          That's the face of your profile - the "About me" section.
          Introduce yourself in the most attractive and inspiring way possible to attract more followers and build lasting relationships.
          This text will appear right next to your profile picture. However you can of course upload another picture, too.
          You can also embed any HTML code or share important links. Be yourself!
          The more natural your online behavior is, the more friends you will make, the more opportunities will follow!
        </div>
        </h2>
        <div class="c">&nbsp;</div>
        <label style="margin-left: 5px;width:80px">About me:</label>
        <textarea name="user_information" cols="" class="richtext" rows="" style=""><?php print $form_values['user_information'];  ?></textarea>
      </div>
    </div>
    <a href="#" class="btn submit">Save changes</a>
    <!--<a href="javascript:;" class="btn submit">SAVE</a>-->
  </form>
  <?php //require (ACTIVE_TEMPLATE_DIR.'users/right_sidebar.php') ?>
</div>
<?php dbg(__FILE__, 1); ?>
