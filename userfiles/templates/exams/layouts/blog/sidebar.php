<div class="blog-sidebar">
  <h2>Categories</h2>
  <br />
  <microweber module="content/category_tree" for_page=<? print PAGE_ID; ?> include_first="1" />
  <br>
  <br>
  <div class="search_holder">
    <microweber module="content/search" />
  </div>
  <br>
  <br>
  <div class="sidebar_subscribe">
    <h2>Share this with your friends</h2>
    <br>
    <div class="sidebar_subscribe_fb">
      <div id="fb-root"></div>
      <script src="http://connect.facebook.net/en_US/all.js#appId=174445032618743&amp;xfbml=1"></script>
      <fb:like href="http://www.facebook.com/Microweber" send="false" width="290" show_faces="true" font="verdana"></fb:like>
    </div>
    <br>
    <div class="sidebar_subscribe_google">
      <table border="0" cellspacing="2" cellpadding="2">
        <tr>
          <td><script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
            <!-- Place this tag where you want the +1 button to render -->
            <g:plusone size="standard" count="true" href="http://microweber.com" ></g:plusone></td>
          <td>+1 us on Google</td>
        </tr>
      </table>
    </div>
    <br>
    <div class="sidebar_subscribe_tw"> <a href="http://twitter.com/Microweber" class="twitter-follow-button">Follow @Microweber</a>
      <script src="http://platform.twitter.com/widgets.js" type="text/javascript"></script>
    </div>
    <br>
    <br>
    <table border="0" cellspacing="5" cellpadding="5">
      <tr>
        <td><a href="http://feeds.feedburner.com/microweber/mw"><img src="http://feeds.feedburner.com/~fc/microweber/mw?bg=99CCFF&amp;fg=444444&amp;anim=1" height="26" width="88" style="border:0" alt="" /></a></td>
        <td><script src="http://www.stumbleupon.com/hostedbadge.php?s=2&r=http://microweber.com"></script></td>
        <td><script type="text/javascript">
        (function() {
          var s = document.createElement('SCRIPT'), s1 = document.getElementsByTagName('SCRIPT')[0];
          s.type = 'text/javascript';
          s.async = true;
          s.src = 'http://widgets.digg.com/buttons.js';
          s1.parentNode.insertBefore(s, s1);
        })();
        </script>
          <a class="DiggThisButton DiggCompact"></a></td>
      </tr>
    </table>
    <br>
    <br>
    <h2>Related posts</h2>
    <br>
    <!--<? print  CATEGORY_ID ?>-->
    <microweber module="posts/list" tn_size="250" category="<? print  CATEGORY_ID ?>" />
  </div>
</div>
