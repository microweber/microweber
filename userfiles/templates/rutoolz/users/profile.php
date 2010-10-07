<div id="RU-subnav">
  <div class="pad3"></div>
  <div id="RU-nav">
    <h1>Profile settings</h1>
      </div>


  <div id="RU-help"> <a class="help" title="Help" href="javascript:void(0)"></a>


  </div>
  <div class="clr"></div>

</div>



<? //require (ACTIVE_TEMPLATE_DIR.'dashboard/dashboard_sidebar.php') ?>
  <div class="pad2"></div>
<div id="profile-main">
  <? /*
  <ul id="about-nav">
    <li class="active"><a href="<? print site_url('dashboard/'); ?>">dashboard</a></li>
    <li><a href="<? print site_url('dashboard/action:circle-of-influence'); ?>">circle of influEnce</a></li>
    <li><a href="<? print site_url('dashboard/action:following'); ?>">following</a></li>
    <li><a href="<? print site_url('dashboard/action:followers'); ?>">followers</a></li>
  </ul>
  */ ?>

<script type="text/javascript">
  $(document).ready(function(){


  });
</script>


    <? $compete_profile = $this->core_model->getParamFromURL ( 'compete_profile' ); ?>
    <? if($compete_profile == 'yes') : ?>
    
    <h1 id="profile-please-complete">Please complete your profile</h1>
     <? endif;  ?>


    <? if(!empty($user_edit_errors)) : ?>
    <ul class="error">
      <? foreach($user_edit_errors as $k => $v) :  ?>
      <li><? print $v ?></li>
      <? endforeach; ?>
    </ul>
    <? endif ?>
    <? if($user_edit_done == true) : ?>
    <h2>Profile updated!</h2>
    <? endif ?>
    <form action="<? print site_url('users/user_action:profile'); ?>" method="post" enctype="multipart/form-data" id="profileForm">
       <div class="settingsItem blocklabels">

               <div class="left" style="width: 460px">
               <h2>Main information</h2>


               <div class="item">
                <label>Username: </label>
                <span class="field"><input name="username"  type="text" value="<? print $form_values['username'];  ?>" /></span>
              </div>


              <div class='c'>&nbsp;</div>
              <div class="item">
                <label>First name: </label>
                <span class="field"><input name="first_name" type="text" value="<? print $form_values['first_name'];  ?>" /></span>
              </div>

              <div class="item">
                <label>Last name: </label>
                <span class="field"><input name="last_name" type="text" value="<? print $form_values['last_name'];  ?>" /></span>
              </div>
              <div class='c'>&nbsp;</div>
              <div class="item">
                <label>Website:  </label>
                <span class="field"><input name="user_website" type="text" value="<? print $form_values['user_website'];  ?>" /> </span>
              </div>
               <div class='c'>&nbsp;</div>
              <div class="item">
                <label>New password:</label>
                <span class="field"><input name="password" type="password" value="<? print $form_values['password'];  ?>" /></span>
              </div>
              <div class='c'>&nbsp;</div>



               <div class="item" name="user_image">
                    <label> Picture/Logo: </label>
                    <input class="cinput input_Up" name="picture_0" style="height:auto" type="file" />
                  <? $thumb = $this->users_model->getUserThumbnail( $form_values['id'], 128); ?>
                    <? if($thumb != ''): ?>
                    <img id='user_image' src="<? print $thumb; ?>" /> <br />
                    <a id='user_image_href' href="javascript:userPictureDelete('<?php echo $form_values['id']?>')">Delete photo</a>
                    <? endif; ?>

               </div>


     </div>


    <div class="right" style="width: 460px">


                <h2>Personal Information</h2>


            <div class="item">
                <label>Country:</label>
                <span class="field"><input name="country" type="text" value="<? print $form_values['country'];  ?>" /></span>
             </div>

            <div class="item">
            <label>City:  </label>
            <span class="field"><input name="city" type="text" value="<? print $form_values['city'];  ?>" *></span>
          </div>
           <div class='c'>&nbsp;</div>
          <div class="item">
            <label>Adress: </label>
            <span class="field"><textarea name="addr1" cols="" rows=""><? print $form_values['addr1'];  ?></textarea></span>
          </div>

          <div class="item">
            <label>Zip:  </label>
            <span class="field"><input name="zip" type="text" value="<? print $form_values['zip'];  ?>" /></span>
          </div>
          <div class='c'>&nbsp;</div>
          <div class="item">
            <label>Phone:  </label>
            <span class="field"><input name="phone" type="text" value="<? print $form_values['phone'];  ?>" /> </span>
          </div>






       </div>
       </div>









      <div class="c">&nbsp;</div>



      <a href="#" class="btn submit left" style="margin: 10px -2px;">Save changes</a>
      <!--<a href="javascript:;" class="btn submit">SAVE</a>-->
    </form>
  <? //require (ACTIVE_TEMPLATE_DIR.'users/right_sidebar.php') ?>
</div>
