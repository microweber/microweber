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
     
    <br>
    <br>
    <h2>Related posts</h2>
    <br>
    <!--<? print  CATEGORY_ID ?>-->
    <microweber module="posts/list" tn_size="none" category="<? print  CATEGORY_ID ?>" />
  </div>
</div>
