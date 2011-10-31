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
    
    
    <br>
    <br>
    <h2>Related posts</h2>
    <br>
    <!--<? print  CATEGORY_ID ?>-->
    <microweber module="posts/list" tn_size="none" category="<? print  CATEGORY_ID ?>" />
  </div>
</div>
