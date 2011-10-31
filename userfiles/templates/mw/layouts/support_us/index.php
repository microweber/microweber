<?php

/*

type: layout

name: support_us layout

description: support_us site layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>

<div class="content_wide_holder">
  <div class="content_center" style="margin-top:10px;">
    <table width="90%" border="0" cellspacing="0" cellpadding="0">
      <tr valign="top">
        <td><img src="<? print TEMPLATE_URL ?>img/heart.png" height="108" /></td>
        <td><div style="margin-top:10px;">
            <h1 class="headers_23">To make Internet a better place we need your help right now!</h1>
            <center>
              <div class="space_top_5"> <span class="text_13">Supporting us will allow many people around the world to create websites and express easily on the Internet</span> </div>
              <div class="space_top_5"> <span><strong class="text_14">Be part of the revolution</strong></span> <a class="blue">Support us now</a> </div>
              <div class="space_top_5"> <img src="<? print TEMPLATE_URL ?>img/thank_you.png"  /> </div>
              <br />
            </center>
          </div></td>
      </tr>
    </table>
  </div>
</div>
 
<div class="content_wide_holder content_wide_holder_bl">
  <div class="content_center">
    <div class="content_center_center">
      <table width="721" border="0" cellspacing="2" cellpadding="0" align="center">
        <tr>
          <td><div id="fb-root"></div>
            <script src="http://connect.facebook.net/en_US/all.js#appId=225342984166233&amp;xfbml=1"></script>
            <fb:like href="https://www.facebook.com/Microweber" send="true" width="500" show_faces="false" font=""></fb:like>
            <div class="space_top_5"> <a href="http://twitter.com/Microweber" class="twitter-follow-button">Follow @Microweber</a>
              <script src="http://platform.twitter.com/widgets.js" type="text/javascript"></script>
            </div></td>
          <td>&nbsp;</td>
        </tr>
      </table>
    </div>
  </div>
</div>
<div class="content_wide_holder_white content_center_home_2">
  <div class="content_center">
    <div class="content_center_center richtext" style="margin-top:39px;" >
      <editable rel="page" field="content_body"> Edit here </editable>
    </div>
  </div>
</div>
<? include   TEMPLATE_DIR.  "footer.php"; ?>
