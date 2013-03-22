

<form action="#">
    <input type="text" class="input-large search-query" placeholder="<?php _e("Search"); ?>">
</form>


<h4 class="sidebar-title">Pages</h4>
<div class="sidebar-box">
  <module type="pages" template="pills" />
</div>
<h4 class="sidebar-title">Blog Categories</h4>
<div class="sidebar-box">
  <module type="categories" />
</div>
<div class="edit"  field="blog_sidebar" rel="inherit"></div>