<?php

/*

  type: layout
  content_type: static
  name: Profile
  description: Profile layout

*/

?>

 <?php include "header.php"; ?>

 <?php if(  !user_id() ){   ?>
  <script> window.location.href = "<?php print mw_site_url() ?>login"; </script>
 <?php } ?>

    <div class="container">
       <div class="main">
       <?php if( user_id() != false){  ?>
             <?php   $user = get_user(user_id());  ?>
             <div class="row" id="user-profile-page">
                <div class="span3" id="user-image">
                    <h5>Hello, <?php print user_name($user['id']); ?></h5>
                    <img id="profile-image" src="<?php print thumbnail($user['thumbnail'], 300, 300); ?>" alt="" />
                    <span class="btn btn-mini" id="user-image-uploader">Change</span>
                    <input type="hidden" name="thumbnail" id="thumbnail" value="<?php print $user['thumbnail']; ?>" />
                    <input type="hidden" name="id" value="<?php print $user['id']; ?>" />
                </div>
                <div class="span9">
                    <div class="bbox">
                      <div class="bbox-content">
                          <ul class="nav nav-tabs" id="user-tabs-nav">
                              <li class="active"><a href="#">My Sites</a></li>
                              <li><a href="#">Account Information</a></li>
                              <li><a href="#">Payment Information</a></li>
                              <li><a href="#">Invoices</a></li>
                          </ul>
                          <div class="user-tab">
                              <h2>My Sites</h2>
                              <module type="whmcs" template="client_products" />
                          </div>
                          <div class="user-tab" style="display: none">
                            <p>Account type: <strong class="blue">Free</strong>&nbsp;&nbsp;&nbsp;<a href="javascript:;" class="btn btn-info btn-mini">Upgrade</a>  </p>
                            <div class="controlls">
                                <label>Email</label>
                                <input type="text" value="<?php print $user['email']; ?>" name="email"  />
                            </div>
                            <div class="controlls">
                                <label>First Name</label>
                                <input type="text" value="<?php print $user['first_name']; ?>" name="first_name" />
                            </div>

                            <div class="controlls">
                                <label>Last Name</label>
                                <input type="text" value="<?php print $user['last_name']; ?>" name="last_name"  />
                            </div>
                            <hr>
                            <input type="submit" value="Save" class="btn pull-right" onclick="save_user_data()" />
                              <a href="javascript:;" class="btn btn-link">Change Password</a>
                          </div>
                           <div class="user-tab" style="display: none">
                                <h5>Payment Information</h5>
                                <module type="shop/payments" />
                           </div>
                           <div class="user-tab" style="display: none">
                                <h5>Invoices</h5>
                            </div>
                      </div>
                    </div>
                </div>
             </div>
             <script>
                mw.require("files.js");
                mw.require("forms.js");
             </script>
             <script>
             save_user_data = function(){
               mw.form.post('#user-profile-page', mw.settings.api_url + "save_user");
             }
             $(document).ready(function(){
                var upload_btn = mwd.getElementById('user-image-uploader');
                var up = mw.files.uploader({
                  multiple:"false"
                });

                $(up).bind("FileUploaded", function(a,b){
                  mw.$("#profile-image").attr("src", b.src);
                  mw.$("#thumbnail").val(b.src);
                });

                $(upload_btn).append(up);
                mw.tools.tabGroup({
                  nav:"#user-tabs-nav li",
                  tabs:".user-tab",
                });
             });
             </script>
          <?php  } ?>
       </div>
    </div>
 <?php include "footer.php"; ?>