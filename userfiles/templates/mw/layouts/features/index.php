<?php

/*

type: layout

name: features layout

description: features site layout









*/

 

?>
<? include TEMPLATE_DIR. "header.php"; ?>

<div class="content_wide_holder_white2 features_page">
  <div class="content_center" style="margin-top:60px; margin-bottom:30px;">
    <?  if(intval($page['content_parent']) == 0): ?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr valign="top">
        <td><h1>Open source</h1>
          <br />
          <h2>Microweber is free and accesible to all.</h2>
          <p> <br />
            We think this is of great importance for the evolution of internet. <br />
            <br />
            We believe in that everybody should use the web as their advantage. <br />
            <br />
            Many people are  not sure how to use the power of the Internet <br />
            and they think its complicated and expensive, but they are not right! <br />
            <br />
          <h3>We are here to make creation of webites easier</h3>
          </p></td>
        <td><a href="http://demo.microweber.com"  target="_blank"><img src="<? print TEMPLATE_URL ?>img/microweber_banner_1.png" border="0" /></a></td>
      </tr>
    </table>
    <br />

    <h1>Browse our features below</h1>
    <microweber module="content/pages_tree"  ul_class="features_nav_big" thumbnail="true" />
    <br />
    <br />

    <h1>How it works</h1>
    <br />

    <p> Microweber is separated on two parts. <br />
      <br />
      We have "admin panel" or "backend" part  and "live edit" mode witch we call "front end". <br />
      <br />
      See the video below, it's a great example for what you can do with Microweber. <br />
      <br />
      Also share the video so everybody can see the system </p>
    <br />
    <br />
    <br />
    <table border="0" cellspacing="4" cellpadding="4">
      <tr valign="middle">
        <td><div id="fb-root"></div>
          <script src="http://connect.facebook.net/en_US/all.js#appId=175097989220402&amp;xfbml=1"></script>
          <fb:like href="https://www.facebook.com/Microweber" send="false" width="300" show_faces="false" action="recommend"></fb:like></td>
        <td><small>Subscribe at </small><a href="http://www.youtube.com/user/microweber"  target="_blank"><img src="<? print TEMPLATE_URL ?>img/youtube.png" border="0" /></a></td>
        <td>&nbsp;</td>
        <td><a href="http://twitter.com/Microweber" class="twitter-follow-button" data-show-count="true">Follow @Microweber</a>
          <script src="http://platform.twitter.com/widgets.js" type="text/javascript"></script></td>
      </tr>
    </table>
    <br />
    <iframe width="960" height="750" src="http://www.youtube.com/embed/8UrvW8Y605M?rel=0" frameborder="0" allowfullscreen style="padding:10px; border:1px solid #c9c9c9;"></iframe>
    <? else : ?>
    <?  include TEMPLATE_DIR. "layouts/features/inner.php"; ?>
    <? endif; ?>
  </div>
</div>
<? include   TEMPLATE_DIR.  "footer.php"; ?>
